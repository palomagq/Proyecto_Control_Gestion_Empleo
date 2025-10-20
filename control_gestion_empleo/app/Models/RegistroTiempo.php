<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroTiempo extends Model
{
    use HasFactory;

    protected $table = 'tabla_registros_tiempo';

    protected $fillable = [
        'empleado_id',
        'inicio',
        'fin',
        'tiempo_total',
        'tiempo_pausa_total',
        'estado',
        'direccion',
        'ciudad',
        'pais',
        'latitud',
        'longitud',
        'precision_gps',
        'dispositivo'
    ];

    protected $casts = [
        'inicio' => 'datetime',
        'fin' => 'datetime',
    ];

    // Relación con Empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
}