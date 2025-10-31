<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $table = 'tabla_tareas';
    
    protected $fillable = [
        'titulo',
        'descripcion',
        'area',
        'tipo_tarea_id',
        'prioridad',
        'estado',
        'fecha_limite',
        'creador_tipo',
        'admin_creador_id',
        'empleado_creador_id'
    ];

    protected $casts = [
        'fecha_limite' => 'date'
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoTarea::class, 'tipo_tarea_id');
    }

    public function adminCreador()
    {
        return $this->belongsTo(Admin::class, 'admin_creador_id');
    }

    public function empleadoCreador()
    {
        return $this->belongsTo(Empleado::class, 'empleado_creador_id');
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionTarea::class, 'tarea_id');
    }

    public function empleadosAsignados()
    {
        return $this->belongsToMany(Empleado::class, 'tabla_asignaciones_tareas', 'tarea_id', 'empleado_id')
                    ->withPivot('estado_asignacion', 'comentarios', 'fecha_completado')
                    ->withTimestamps();
    }

    // Scope para tareas de admin
    public function scopeDeAdmin($query, $adminId)
    {
        return $query->where('creador_tipo', 'admin')
                    ->where('admin_creador_id', $adminId);
    }

    // Scope para tareas de empleado
    public function scopeDeEmpleado($query, $empleadoId)
    {
        return $query->where('creador_tipo', 'empleado')
                    ->where('empleado_creador_id', $empleadoId);
    }

    // Scope para tareas asignadas a un empleado
    public function scopeAsignadasAEmpleado($query, $empleadoId)
    {
        return $query->whereHas('asignaciones', function($q) use ($empleadoId) {
            $q->where('empleado_id', $empleadoId);
        });
    }
}