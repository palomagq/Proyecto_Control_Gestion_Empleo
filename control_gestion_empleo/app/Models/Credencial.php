<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Credencial extends Authenticatable
{
    use HasFactory, Notifiable;

    // Especificar la tabla correcta
    protected $table = 'tabla_credenciales';

    protected $fillable = [
        'username',
        'password',
        'rol_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relación con Rol
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    /**
     * Relación con Empleado (UNO A UNO - el empleado tiene credencial_id)
     */
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'credencial_id');
    }

    // Métodos helper para verificar roles
    public function esAdmin()
    {
        return $this->rol && $this->rol->nombre === 'Administrador';
    }

    public function esEmpleado()
    {
        return $this->rol && $this->rol->nombre === 'Empleado';
    }

    /**
     * Obtener el nombre completo para mostrar
     */
    public function getNombreCompletoAttribute()
    {
        if ($this->empleado) {
            return $this->empleado->nombre . ' ' . $this->empleado->apellidos;
        }
        return $this->username;
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * Set the token value for the "remember me" session.
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}