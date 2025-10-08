<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Credencial extends Authenticatable
{
    use HasFactory;

    protected $table = 'tabla_credenciales';

    protected $fillable = [
        'username',
        'password',
        'rol_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relación: Una credencial pertenece a un rol
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    /**
     * Relación: Una credencial puede tener un empleado (si es empleado)
     */
    public function employee()
    {
        return $this->hasOne(Empleado::class, 'credencial_id');
    }

    /**
     * Relación: Una credencial puede tener un admin (si es admin)
     */
    public function admin()
    {
        return $this->hasOne(Admin::class, 'credencial_id');
    }

    /**
     * Verificar si la credencial es de un admin
     */
    public function esAdmin()
    {
        return $this->rol && $this->rol->nombre === 'admin';
    }

    /**
     * Verificar si la credencial es de un empleado
     */
    public function esEmpleado()
    {
        return $this->rol && $this->rol->nombre === 'empleado';
    }

    /**
     * Obtener el usuario asociado (admin o empleado)
     */
    public function usuario()
    {
        if ($this->esAdmin()) {
            return $this->admin;
        } elseif ($this->esEmpleado()) {
            return $this->employee;
        }
        
        return null;
    }

    /**
     * Obtener el nombre del usuario según su tipo
     */
    public function getNombreUsuarioAttribute()
    {
        $usuario = $this->usuario();
        
        if ($usuario) {
            if ($this->esAdmin()) {
                return $usuario->nombre ?? $this->username;
            } elseif ($this->esEmpleado()) {
                return $usuario->nombre_completo ?? $this->username;
            }
        }
        
        return $this->username;
    }
}