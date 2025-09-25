<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Credencial;
use App\Models\Rol; 
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
      $this->command->info('🔍 Iniciando seeder de administrador...');

        // 1. Verificar si la tabla roles existe y insertar roles si es necesario
        try {
            $rolesExist = DB::select("SELECT 1 FROM tabla_roles LIMIT 1");
        } catch (\Exception $e) {
            $this->command->error('❌ La tabla roles no existe. Ejecuta las migraciones primero.');
            return;
        }

        // Insertar roles si no existen
        $adminRole = DB::table('tabla_roles')->where('nombre', 'admin')->first();
        $empleadoRole = DB::table('tabla_roles')->where('nombre', 'empleado')->first();

        if (!$adminRole) {
            $adminRoleId = DB::table('tabla_roles')->insertGetId([
                'nombre' => 'admin',
                'descripcion' => 'Administrador del sistema',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $this->command->info('✅ Rol admin creado');
        } else {
            $adminRoleId = $adminRole->id;
            $this->command->info('ℹ️ Rol admin ya existe');
        }

        if (!$empleadoRole) {
            DB::table('tabla_roles')->insert([
                'nombre' => 'empleado',
                'descripcion' => 'Empleado de la empresa',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $this->command->info('✅ Rol empleado creado');
        } else {
            $this->command->info('ℹ️ Rol empleado ya existe');
        }

        // 2. Verificar y crear credenciales de admin
        $adminCredencial = DB::table('tabla_credenciales')->where('username', 'admin')->first();

        if (!$adminCredencial) {
            $credencialId = DB::table('tabla_credenciales')->insertGetId([
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'rol_id' => $adminRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info('✅ Credenciales de admin creadas');
        } else {
            $credencialId = $adminCredencial->id;
            $this->command->info('ℹ️ Credenciales de admin ya existen');
        }

        // 3. Verificar y crear registro de admin
        $adminExists = DB::table('tabla_admin')->where('credencial_id', $credencialId)->exists();

        if (!$adminExists) {
            DB::table('tabla_admin')->insert([
                'credencial_id' => $credencialId,
                'rol_id' => $adminRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info('✅ Registro de admin creado');
        } else {
            $this->command->info('ℹ️ Registro de admin ya existe');
        }

        $this->command->info('🎉 Proceso completado exitosamente!');
        $this->command->info('📋 Credenciales de acceso:');
        $this->command->info('   Username: admin');
        $this->command->info('   Password: admin123');
    }
}
