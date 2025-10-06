<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
            $this->command->error('❌ Archivo JSON no encontrado: ' . $jsonFile);
            return;
        }
        echo "✅ Archivo JSON encontrado\n";

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

        $this->command->info('🔄 Total registros en JSON: ' . count($empleadosData));

        // **VERIFICAR EMPLEADOS EXISTENTES (SOLO PARA INFO)**
        $empleadosExistentes = DB::table('tabla_empleados')->count();
        $credencialesExistentes = DB::table('tabla_credenciales')->count();
        
        $this->command->info("📊 Empleados existentes en BD: " . $empleadosExistentes);
        $this->command->info("📊 Credenciales existentes en BD: " . $credencialesExistentes);

        // **OBTENER DNIs y USERNAMES EXISTENTES para evitar duplicados**
        $dnisExistentes = DB::table('tabla_empleados')->pluck('dni')->toArray();
        $usernamesExistentes = DB::table('tabla_credenciales')->pluck('username')->toArray();

        $this->command->info("🎯 DNIs existentes: " . count($dnisExistentes));
        $this->command->info("🎯 Usernames existentes: " . count($usernamesExistentes));

        // **FILTRAR NUEVOS REGISTROS (que no existen en BD)**
        $nuevosEmpleados = [];
        $duplicadosEncontrados = 0;

        foreach ($empleadosData as $data) {
            $dni = $data['dni'];
            
            // **EXTRAER SOLO LOS NÚMEROS DEL DNI para username**
            $username = $this->extraerNumerosDni($dni);
            
            // Verificar si ya existe por DNI o username
            $existePorDni = in_array($dni, $dnisExistentes);
            $existePorUsername = in_array($username, $usernamesExistentes);
            
            if (!$existePorDni && !$existePorUsername) {
                $nuevosEmpleados[] = [
                    'data' => $data,
                    'username' => $username
                ];
            } else {
                $duplicadosEncontrados++;
                if ($existePorDni) {
                    $this->command->info("⏭️ DNI duplicado omitido: {$dni} - {$data['nombre']}");
                } else {
                    $this->command->info("⏭️ Username duplicado omitido: {$username} - {$data['nombre']}");
                }
            }
        }

        $this->command->info("✅ Nuevos registros a insertar: " . count($nuevosEmpleados));
        $this->command->info("⏭️ Duplicados omitidos: " . $duplicadosEncontrados);

        if (count($nuevosEmpleados) === 0) {
            $this->command->info('🎉 No hay nuevos registros para insertar. Los datos existentes se preservaron.');
            return;
        }

        $insertados = 0;
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

        $rol = DB::table('tabla_roles')->where('nombre', 'empleado')->first();
        $rolId = $rol ? $rol->id : 2;

        foreach ($nuevosEmpleados as $item) {
            try {
                $data = $item['data'];
                $username = $item['username'];
                
                // **VERIFICAR ÚLTIMA VEZ por si hay concurrencia**
                $dniExiste = DB::table('tabla_empleados')->where('dni', $data['dni'])->exists();
                $usernameExiste = DB::table('tabla_credenciales')->where('username', $username)->exists();
                
                if ($dniExiste || $usernameExiste) {
                    $this->command->info("⏭️ Duplicado detectado durante inserción: {$data['dni']}");
                    continue;
                }

                // Convertir fecha
                $fechaFormateada = $this->convertirFecha($data['fecha_nacimiento']);

                $now = now();

                // **INSERTAR CREDENCIAL (SOLO NÚMEROS DEL DNI)**
                $credencialId = DB::table('tabla_credenciales')->insertGetId([
                    'username' => $username,
                    'password' => bcrypt('1234'), // Contraseña por defecto
                    'rol_id' => $rolId,
                    'created_at' => $now,
                    'updated_at' => $now
                ]);

                // **INSERTAR EMPLEADO**
                DB::table('tabla_empleados')->insert([
                    'nombre' => $data['nombre'],
                    'apellidos' => $data['apellidos'],
                    'dni' => $data['dni'],
                    'fecha_nacimiento' => $fechaFormateada,
                    'domicilio' => $data['domicilio'] ?? 'Sin dirección',
                    'latitud' => $data['latitud'] ?? 40.4168,
                    'longitud' => $data['longitud'] ?? -3.7038,
                    'credencial_id' => $credencialId,
                    'rol_id' => $rolId,
                    'created_at' => $now,
                    'updated_at' => $now
                ]);

                $insertados++;
                
                $this->command->info("✅ Nuevo empleado: {$data['nombre']} {$data['apellidos']}");
                $this->command->info("   - DNI: {$data['dni']}");
                $this->command->info("   - Username: {$username}");
                $this->command->info("   - Contraseña: 1234");
                
                if ($insertados % 10 == 0) {
                    $this->command->info("📊 {$insertados} nuevos registros insertados...");
                }

            } catch (\Exception $e) {
                $errores++;
                $this->command->error("❌ Error con {$data['dni']}: " . $e->getMessage());
            }
        }

        // **VERIFICACIÓN FINAL**
        $totalFinalEmpleados = DB::table('tabla_empleados')->count();
        $totalFinalCredenciales = DB::table('tabla_credenciales')->count();

        $this->command->info("\n🎉 PROCESO COMPLETADO");
        $this->command->info("=================================");
        $this->command->info("✅ Nuevos empleados insertados: {$insertados}");
        $this->command->info("❌ Errores durante inserción: {$errores}");
        $this->command->info("⏭️ Duplicados omitidos: {$duplicadosEncontrados}");
        $this->command->info("📊 TOTAL FINAL:");
        $this->command->info("   - Empleados en BD: {$totalFinalEmpleados}");
        $this->command->info("   - Credenciales en BD: {$totalFinalCredenciales}");
        $this->command->info("   - Incremento: +{$insertados} empleados");
        $this->command->info("=================================");
    }

    /**
     * Extrae solo los números del DNI (primeros 8 dígitos)
     */
    private function extraerNumerosDni($dni)
    {
        // Extraer solo los primeros 8 dígitos numéricos
        preg_match('/^\d{8}/', $dni, $matches);
        
        if (isset($matches[0])) {
            return $matches[0];
        }
        
        // Si no encuentra 8 dígitos, intentar extraer todos los números
        preg_match_all('/\d+/', $dni, $matches);
        $numeros = implode('', $matches[0]);
        
        // Tomar solo los primeros 8 caracteres numéricos
        return substr($numeros, 0, 8);
    }

    /**
     * Convierte fecha de d/m/Y a Y-m-d
     */
    private function convertirFecha($fecha)
    {
        $fechaParts = explode('/', $fecha);
        if (count($fechaParts) == 3) {
            return $fechaParts[2] . '-' . $fechaParts[1] . '-' . $fechaParts[0];
        }
        return '1990-01-01'; // Fecha por defecto
    }
}