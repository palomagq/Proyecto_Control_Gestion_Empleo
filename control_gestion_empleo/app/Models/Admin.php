<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    //
    use HasFactory;

     // Especificar el nombre de la tabla
    protected $table = 'tabla_admin'; // o el nombre que tengas en tu BD

    protected $fillable = [
        'credencial_id',
        'rol_id',
        'nombre', // Agregar este campo si no existe
        'email',  // Agregar este campo si no existe
    ];

    /**
     * Relación: Un admin pertenece a un rol
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    /**
     * Relación: Un admin pertenece a una credencial
     */
    public function credencial()
    {
        return $this->belongsTo(Credencial::class, 'credencial_id');
    }

    /**
     * Obtener el username del admin a través de la credencial
     */
    public function getUsernameAttribute()
    {
        return $this->credencial->username;
    }

    
    /**
     * Obtener el admin único del sistema
     */
    public static function obtenerAdmin()
    {
        return self::with(['credencial', 'rol'])->first();
    }

    /**
     * Crear un nuevo admin con credenciales
     */
  /*  public static function crearConCredenciales($username, $password, $rolNombre = 'admin')
    {
        $rol = Rol::where('nombre', $rolNombre)->first();
        
        if (!$rol) {
            throw new \Exception("Rol {$rolNombre} no encontrado");
        }

        // Crear credencial
        $credencial = Credencial::create([
            'username' => $username,
            'password' => bcrypt($password),
            'rol_id' => $rol->id,
        ]);

        // Crear admin
        return self::create([
            'credencial_id' => $credencial->id,
            'rol_id' => $rol->id,
        ]);
    }*/

    /**
     * Scope para buscar admin por username de credencial
     */
   /* public function scopePorUsername($query, $username)
    {
        return $query->whereHas('credencial', function($q) use ($username) {
            $q->where('username', $username);
        });
    }*/
}
