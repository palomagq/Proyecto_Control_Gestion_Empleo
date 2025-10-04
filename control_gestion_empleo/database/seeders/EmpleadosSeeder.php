<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;
use App\Models\Credencial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EmpleadosSeeder extends Seeder
{
    public function run()
    {
        echo "🚀 INICIANDO SEEDER EMPLEADOS...\n";
        
        // Ruta al archivo JSON
        $jsonFile = database_path('seeders/data/empleados_data.json');
        
        echo "📁 Buscando archivo: " . $jsonFile . "\n";
        
        if (!File::exists($jsonFile)) {
            echo "❌ ERROR: Archivo JSON no encontrado\n";
            return;
        }
        echo "✅ Archivo JSON encontrado\n";

        // Leer archivo JSON
        $jsonData = File::get($jsonFile);
        $empleadosData = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "❌ ERROR decodificando JSON: " . json_last_error_msg() . "\n";
            return;
        }

        echo "📊 Total registros en JSON: " . count($empleadosData) . "\n";

        // Obtener rol_id
        $rolEmpleado = DB::table('tabla_roles')->where('nombre', 'empleado')->first();
        
        if (!$rolEmpleado) {
            echo "❌ ERROR: No se encontró el rol 'empleado'\n";
            return;
        }
        
        $rolId = $rolEmpleado->id;
        echo "🎯 Rol ID para empleados: " . $rolId . "\n";

        $insertados = 0;
        $saltados = 0;
        $errores = 0;

        echo "🔄 Procesando registros...\n";

        foreach ($empleadosData as $index => $data) {
            $numeroRegistro = $index + 1;
            
            try {
                // Validar datos esenciales
                if (empty($data['dni']) || empty($data['nombre']) || empty($data['apellidos']) || empty($data['telefono'])) {
                    echo "⏭️ Registro {$numeroRegistro} omitido - datos incompletos\n";
                    $saltados++;
                    continue;
                }

                // GENERAR USERNAME A PARTIR DEL DNI (sin la letra)
                $username = substr($data['dni'], 0, 8);

                // Verificar si ya existe
                $existeEmpleado = Empleado::where('dni', $data['dni'])->exists();
                $existeUsername = Credencial::where('username', $username)->exists();

                if ($existeEmpleado || $existeUsername) {
                    echo "⏭️ Registro {$numeroRegistro} omitido - ya existe (DNI: {$data['dni']})\n";
                    $saltados++;
                    continue;
                }

                // **CONVERTIR FECHA al formato MySQL (Y-m-d)**
                $fechaNacimiento = null;
                try {
                    $fechaNacimiento = Carbon::createFromFormat('d/m/Y', $data['fecha_nacimiento'])->format('Y-m-d');
                    echo "✅ Fecha convertida: {$data['fecha_nacimiento']} -> {$fechaNacimiento}\n";
                } catch (\Exception $e) {
                    echo "❌ Error convirtiendo fecha: {$data['fecha_nacimiento']} - " . $e->getMessage() . "\n";
                    $errores++;
                    continue;
                }

                // **CREAR CREDENCIAL**
                $credencial = new Credencial();
                $credencial->username = $username;
                $credencial->password = bcrypt('1234');
                $credencial->rol_id = $rolId;
                $credencial->save();

                echo "✅ Credencial creada - ID: " . $credencial->id . "\n";

                // **CREAR EMPLEADO**
                $empleado = new Empleado();
                $empleado->nombre = $data['nombre'];
                $empleado->apellidos = $data['apellidos'];
                $empleado->dni = $data['dni'];
                $empleado->fecha_nacimiento = $fechaNacimiento; // Usar fecha convertida
                $empleado->domicilio = $data['domicilio'];
                $empleado->telefono = $data['telefono'];
                $empleado->latitud = $data['latitud'] ?? 40.4168;
                $empleado->longitud = $data['longitud'] ?? -3.7038;
                $empleado->credencial_id = $credencial->id;
                $empleado->rol_id = $rolId;
                $empleado->save();

                echo "🎉 EMPLEADO CREADO - ID: " . $empleado->id . " - {$data['nombre']} {$data['apellidos']}\n";
                $insertados++;

            } catch (\Exception $e) {
                $errores++;
                echo "❌ ERROR en registro {$numeroRegistro} (DNI: {$data['dni']}): " . $e->getMessage() . "\n";
                continue;
            }
        }

        // Resumen final
        echo "\n🎉 SEEDER COMPLETADO\n";
        echo "=================================\n";
        echo "📊 RESUMEN FINAL:\n";
        echo "✅ Insertados: {$insertados}\n";
        echo "⏭️ Saltados: {$saltados}\n";
        echo "❌ Errores: {$errores}\n";
        echo "📄 Total procesado: " . count($empleadosData) . "\n";
        echo "=================================\n";
    }
}