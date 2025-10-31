<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionTarea extends Model
{
    use HasFactory;

    protected $table = 'tabla_asignaciones_tareas';
    
    protected $fillable = [
        'tarea_id',
        'empleado_id',
        'estado_asignacion',
        'comentarios',
        'fecha_completado'
    ];

    protected $casts = [
        'fecha_completado' => 'datetime'
    ];

    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'tarea_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
}