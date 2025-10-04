<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    //
    use HasFactory;

    protected $table = 'tabla_empleados';

    protected $fillable = [
        'nombre',
        'apellidos',
        'fecha_nacimiento',
        'dni',
        'domicilio',
        'telefono', // Nuevo campo
        'latitud',
        'longitud',
        'credencial_id',
        'qr_id', // Nuevo campo
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
/**
 * Crear un nuevo empleado con credenciales
 */
/**
 * Crear un nuevo empleado con credenciales automáticas
 * - Contraseña: primeros 4 dígitos del DNI
 * - Validación: mayor de 16 años
 */
public static function crearConCredenciales($datosEmpleado)
{
    $rolEmpleado = Rol::where('nombre', 'empleado')->first();
    
    if (!$rolEmpleado) {
        throw new \Exception("Rol de empleado no encontrado");
    }

    // VALIDAR EDAD: Mayor de 16 años
    $fechaNacimiento = new \DateTime($datosEmpleado['fecha_nacimiento']);
    $hoy = new \DateTime();
    $edad = $hoy->diff($fechaNacimiento)->y;
    
    if ($edad < 16) {
        throw new \Exception("El empleado debe ser mayor de 16 años. Edad actual: {$edad} años");
    }

    // Generar username (DNI sin letra)
    $username = substr($datosEmpleado['dni'], 0, 8);
    
    // GENERAR CONTRASEÑA: primeros 4 dígitos del DNI
    $password = substr($datosEmpleado['dni'], 0, 4);
    
    // Validar que la contraseña tenga exactamente 4 dígitos
    if (!preg_match('/^\d{4}$/', $password)) {
        throw new \Exception("El DNI debe comenzar con 4 dígitos para generar la contraseña automáticamente");
    }

    // Verificar que el username no exista
    if (\App\Models\Credencial::where('username', $username)->exists()) {
        throw new \Exception("El nombre de usuario '{$username}' ya existe");
    }

    // Verificar que el DNI no exista
    if (self::where('dni', $datosEmpleado['dni'])->exists()) {
        throw new \Exception("El DNI '{$datosEmpleado['dni']}' ya está registrado");
    }

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
        'telefono' => $datosEmpleado['telefono'],
        'latitud' => $datosEmpleado['latitud'] ?? null,
        'longitud' => $datosEmpleado['longitud'] ?? null,
        'credencial_id' => $credencial->id,
        'rol_id' => $rolEmpleado->id,
    ]);

    return [
        'empleado' => $empleado,
        'credencial' => $credencial,
        'password_generado' => $password // Renombrar para mayor claridad
    ];
}

   // Relación con QR
    public function qr()
    {
        return $this->belongsTo(Qr::class, 'qr_id');
    }

}
