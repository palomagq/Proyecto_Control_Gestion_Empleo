<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Credencial extends Authenticatable
{
    //
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
     * Relación: Una credencial puede tener un empleado
     */
    public function employee()
    {
        return $this->hasOne(Empleado::class, 'credencial_id');
    }

    /**
     * Relación: Una credencial puede tener un admin
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
        return $this->rol->nombre === 'admin';
    }

    /**
     * Verificar si la credencial es de un empleado
     */
    public function esEmpleado()
    {
        return $this->rol->nombre === 'empleado';
    }

    /**
     * Scope para buscar credencial por username
     */
    public function scopePorUsername($query, $username)
    {
        return $query->where('username', $username);
    }
}
