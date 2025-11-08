<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    // ✅ ESPECIFICAR EL NOMBRE CORRECTO DE LA TABLA
    protected $table = 'tabla_tareas';

    protected $fillable = [
        'titulo',
        'descripcion',
        'tipo_tarea_id',
        'prioridad',
        'fecha_tarea',
        'horas_tarea',
        'area',
        'estado',
        'creador_tipo', // 'admin' o 'empleado'
        'admin_creador_id',
        'empleado_creador_id'
    ];

    protected $casts = [
        'fecha_tarea' => 'date',
        'horas_tarea' => 'decimal:2',
    ];

    // Relación con TipoTarea
    public function tipoTarea()
    {
        return $this->belongsTo(TipoTarea::class, 'tipo_tarea_id');
    }

    // Relación con asignaciones
    public function asignaciones()
    {
        return $this->hasMany(AsignacionTarea::class, 'tarea_id');
    }
    
    public function empleadosAsignados()
    {
        return $this->belongsToMany(Empleado::class, 'tarea_empleado', 'tarea_id', 'empleado_id');
    }

    // Relación con administrador creador (si aplica)
    public function adminCreador()
    {
        return $this->belongsTo(User::class, 'admin_creador_id');
    }

    // Relación con el empleado creador (si es empleado)
    public function empleadoCreador()
    {
        return $this->belongsTo(Empleado::class, 'empleado_creador_id');
    }

     // Accesor para formatear las horas
    public function getHorasTareaFormateadoAttribute()
    {
        $horas = floatval($this->horas_tarea);
        $horasEntero = floor($horas);
        $minutos = round(($horas - $horasEntero) * 60);
        
        if ($minutos == 60) {
            $horasEntero += 1;
            $minutos = 0;
        }
        
        if ($horasEntero > 0 && $minutos > 0) {
            return "{$horasEntero}h {$minutos}m";
        } elseif ($horasEntero > 0) {
            return "{$horasEntero}h";
        } else {
            return "{$minutos}m";
        }
    }

    // Scope para tareas creadas por empleado
    public function scopeCreadasPorEmpleado($query, $empleadoId)
    {
        return $query->where('creador_tipo', 'empleado')
                    ->where('empleado_creador_id', $empleadoId);
    }

    // Scope para tareas asignadas al empleado
    public function scopeAsignadasAEmpleado($query, $empleadoId)
    {
        return $query->whereHas('asignaciones', function($q) use ($empleadoId) {
            $q->where('empleado_id', $empleadoId);
        });
    }
}