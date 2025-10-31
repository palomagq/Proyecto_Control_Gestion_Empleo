<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoTarea extends Model
{
    use HasFactory;

    protected $table = 'tabla_tipos_tarea';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'color',
        'activo'
    ];

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'tipo_tarea_id');
    }
}