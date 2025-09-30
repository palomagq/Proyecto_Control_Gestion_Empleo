<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;
use App\Models\Credencial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class EmpleadosSeeder extends Seeder
{
    public function run()
    {
        // Desactivar logs de consultas para mejor rendimiento
        DB::disableQueryLog();
        
        // Ruta al archivo JSON
        $jsonFile = database_path('seeders/data/empleados_data.json');
        
        if (!File::exists($jsonFile)) {
            Log::error('❌ Archivo JSON no encontrado: ' . $jsonFile);
            return;
        }

        // Leer archivo JSON
        $jsonData = File::get($jsonFile);
        $empleadosData = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('❌ Error decodificando JSON: ' . json_last_error_msg());
            return;
        }

        Log::info('🔄 Iniciando seeder. Total registros en JSON: ' . count($empleadosData));

        $insertados = 0;
        $saltados = 0;
        $errores = 0;

        // Obtener rol_id una sola vez
        $rolEmpleado = DB::table('tabla_roles')->where('nombre', 'empleado')->first();
        $rolId = $rolEmpleado ? $rolEmpleado->id : 2;

        Log::info('🎯 Rol ID para empleados: ' . $rolId);

        // Procesar en lotes más pequeños
        $chunks = array_chunk($empleadosData, 25); // Procesar de 25 en 25

        foreach ($chunks as $chunkIndex => $chunk) {
            Log::info("📦 Procesando lote " . ($chunkIndex + 1) . " de " . count($chunks));
            
            foreach ($chunk as $dataIndex => $data) {
                $numeroRegistro = ($chunkIndex * 25) + $dataIndex + 1;
                
                try {
                    // Validar datos esenciales
                    if (empty($data['dni']) || empty($data['nombre']) || empty($data['apellidos'])) {
                        Log::warning("⏭️ Registro {$numeroRegistro} omitido - datos incompletos", [
                            'dni' => $data['dni'] ?? 'N/A',
                            'nombre' => $data['nombre'] ?? 'N/A'
                        ]);
                        $saltados++;
                        continue;
                    }

                    // Verificar si ya existe (sin transacción)
                    $existeEmpleado = Empleado::where('dni', $data['dni'])->exists();
                    $existeUsername = Credencial::where('username', $data['username'])->exists();

                    if ($existeEmpleado || $existeUsername) {
                        Log::warning("⏭️ Registro {$numeroRegistro} omitido - ya existe", [
                            'dni' => $data['dni'],
                            'username' => $data['username'],
                            'empleado_existe' => $existeEmpleado,
                            'username_existe' => $existeUsername
                        ]);
                        $saltados++;
                        continue;
                    }

                    // **CREAR CREDENCIAL**
                    $credencial = new Credencial();
                    $credencial->username = $data['username'];
                    $credencial->password = bcrypt('1234');
                    $credencial->rol_id = $rolId;
                    $credencial->save();

                    // **CREAR EMPLEADO**
                    $empleado = new Empleado();
                    $empleado->nombre = $data['nombre'];
                    $empleado->apellidos = $data['apellidos'];
                    $empleado->dni = $data['dni'];
                    $empleado->fecha_nacimiento = $data['fecha_nacimiento'];
                    $empleado->domicilio = $data['domicilio'];
                    $empleado->latitud = $data['latitud'] ?? 40.4168;
                    $empleado->longitud = $data['longitud'] ?? -3.7038;
                    $empleado->credencial_id = $credencial->id;
                    $empleado->rol_id = $rolId;
                    $empleado->save();

                    $insertados++;
                    
                    if ($insertados % 10 === 0) {
                        Log::info("✅ {$insertados} registros insertados hasta ahora...");
                    }

                } catch (\Exception $e) {
                    $errores++;
                    Log::error("❌ Error en registro {$numeroRegistro}", [
                        'dni' => $data['dni'] ?? 'N/A',
                        'error' => $e->getMessage(),
                        'linea' => $e->getLine(),
                        'archivo' => $e->getFile()
                    ]);
                    
                    // Continuar con el siguiente registro aunque falle uno
                    continue;
                }
            }
            
            // Pequeña pausa entre lotes
            sleep(1);
        }

        // Resumen final
        Log::info("🎉 SEEDER COMPLETADO");
        Log::info("=================================");
        Log::info("📊 RESUMEN FINAL:");
        Log::info("✅ Insertados: {$insertados}");
        Log::info("⏭️ Saltados: {$saltados}");
        Log::info("❌ Errores: {$errores}");
        Log::info("📄 Total procesado: " . count($empleadosData));
        Log::info("=================================");
    }
}