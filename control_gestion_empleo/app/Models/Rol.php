<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rol extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Relación: Un rol puede tener muchos admins
     */
   /* public function admins()
    {
        return $this->hasMany(Admin::class, 'rol_id');
    }

    /**
     * Relación: Un rol puede tener muchas credenciales
     
    public function credenciales()
    {
        return $this->hasMany(Credencial::class, 'rol_id');
    }

    /**
     * Relación: Un rol puede tener muchos empleados
     
    public function employees()
    {
        return $this->hasMany(Empleado::class, 'rol_id');
    }

    /**
     * Scope para buscar rol por nombre
     
    public function scopePorNombre($query, $nombre)
    {
        return $query->where('nombre', $nombre);
    }*/
}
