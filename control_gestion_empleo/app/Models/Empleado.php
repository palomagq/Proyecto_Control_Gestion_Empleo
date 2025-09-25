<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellidos',
        'fecha_nacimiento',
        'dni',
        'domicilio',
        'latitud',
        'longitud',
        'credencial_id',
        'rol_id',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    /**
     * Relación: Un empleado pertenece a una credencial
     */
    public function credencial()
    {
        return $this->belongsTo(Credencial::class, 'credencial_id');
    }

    /**
     * Relación: Un empleado pertenece a un rol
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    /**
     * Obtener el username del empleado a través de la credencial
     */
    public function getUsernameAttribute()
    {
        return $this->credencial->username;
    }

    /**
     * Obtener el nombre completo del empleado
     */
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellidos;
    }

    /**
     * Calcular la edad del empleado
     */
    public function getEdadAttribute()
    {
        return $this->fecha_nacimiento->age;
    }

    /**
     * Scope para buscar empleados por DNI
     */
    public function scopePorDni($query, $dni)
    {
        return $query->where('dni', 'like', "%{$dni}%");
    }

    /**
     * Scope para buscar empleados por nombre
     */
    public function scopePorNombre($query, $nombre)
    {
        return $query->where('nombre', 'like', "%{$nombre}%");
    }

    /**
     * Scope para buscar empleados por mes de nacimiento
     */
    public function scopePorMesNacimiento($query, $mes, $anio = null)
    {
        if (!$anio) {
            $anio = date('Y');
        }
        
        return $query->whereMonth('fecha_nacimiento', $mes)
                    ->whereYear('fecha_nacimiento', $anio);
    }

    /**
     * Crear un nuevo empleado con credenciales automáticas
     */
    public static function crearConCredenciales($datosEmpleado)
    {
        $rolEmpleado = Rol::where('nombre', 'empleado')->first();
        
        if (!$rolEmpleado) {
            throw new \Exception("Rol de empleado no encontrado");
        }

        // Generar username (DNI sin letra)
        $username = substr($datosEmpleado['dni'], 0, 8);
        
        // Generar password numérico de 6 dígitos
        $password = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Crear credencial
        $credencial = Credencial::create([
            'username' => $username,
            'password' => bcrypt($password),
            'rol_id' => $rolEmpleado->id,
        ]);

        // Crear empleado
        $empleado = self::create([
            'nombre' => $datosEmpleado['nombre'],
            'apellidos' => $datosEmpleado['apellidos'],
            'fecha_nacimiento' => $datosEmpleado['fecha_nacimiento'],
            'dni' => $datosEmpleado['dni'],
            'domicilio' => $datosEmpleado['domicilio'],
            'latitud' => $datosEmpleado['latitud'],
            'longitud' => $datosEmpleado['longitud'],
            'credencial_id' => $credencial->id,
            'rol_id' => $rolEmpleado->id,
        ]);

        return [
            'empleado' => $empleado,
            'password_plain' => $password
        ];
    }
}
