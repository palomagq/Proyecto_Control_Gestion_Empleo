<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rol extends Model
{
    use HasFactory;

    // Especificar el nombre de la tabla
    protected $table = 'tabla_roles'; // o el nombre que tengas en tu BD

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Relación: Un rol puede tener muchas credenciales
     */
    public function credenciales()
    {
        return $this->hasMany(Credencial::class, 'rol_id');
    }

    /**
     * Relación: Un rol puede tener muchos empleados
     */
    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'rol_id');
    }

    /**
     * Relación: Un rol puede tener un admin (solo uno)
     */
    public function admin()
    {
        return $this->hasOne(Admin::class, 'rol_id');
    }
}