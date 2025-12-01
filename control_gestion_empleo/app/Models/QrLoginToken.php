<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrLoginToken extends Model
{
    use HasFactory;

    // Especificar el nombre de la tabla
    protected $table = 'tabla_qr_login_tokens';

    protected $fillable = [
        'token',
        'empleado_id',
        'is_active',
        'is_confirmed',
        'expires_at',
        'confirmed_at'  // ✅ Agregar esta línea
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_confirmed' => 'boolean',
        'expires_at' => 'datetime',
        'confirmed_at' => 'datetime'
    ];

    /**
     * Relación con el empleado
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    /**
     * Relación con el usuario a través del empleado
     */
    public function user()
    {
        // Si tu modelo Empleado tiene relación con User
        return $this->hasOneThrough(
            User::class, 
            Empleado::class, 
            'id',           // Foreign key on empleados table
            'id',           // Foreign key on users table  
            'empleado_id',  // Local key on qr_login_tokens table
            'user_id'       // Local key on empleados table
        );
    }

    /**
     * Scope para tokens activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('expires_at', '>', now());
    }

    /**
     * Scope para tokens confirmados
     */
    public function scopeConfirmed($query)
    {
        return $query->where('is_confirmed', true);
    }

    /**
     * Verificar si el token es válido
     */
    public function isValid()
    {
        return $this->is_active && 
               $this->expires_at->isFuture() && 
               !$this->is_confirmed;
    }

    /**
     * Marcar como confirmado
     */
    public function markAsConfirmed($empleadoId = null)
    {
        $this->update([
            'is_confirmed' => true,
            'is_active' => false,
            'empleado_id' => $empleadoId,
            'confirmed_at' => now()  // ✅ Usar confirmed_at
        ]);
    }
}