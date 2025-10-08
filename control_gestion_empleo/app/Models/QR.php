<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{
    use HasFactory;

    protected $table = 'tabla_qr';

    protected $fillable = [
        'imagen_qr',
        'codigo_unico',
        'contenido_qr'
    ];

    // Relación con empleados
    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'qr_id');
    }

    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'qr_id');
    }

    /**
     * Mutator para almacenar la imagen QR
     */
    public function setImagenQrAttribute($value)
    {
        if (is_string($value) && preg_match('/^[a-zA-Z0-9\/+]*={0,2}$/', $value)) {
            $this->attributes['imagen_qr'] = base64_decode($value);
        } else {
            $this->attributes['imagen_qr'] = $value;
        }
    }

    /**
     * Accesor para obtener la imagen como base64
     */
    public function getImagenQrBase64Attribute()
    {
        if ($this->imagen_qr) {
            if (is_resource($this->imagen_qr)) {
                return base64_encode(stream_get_contents($this->imagen_qr));
            }
            return base64_encode($this->imagen_qr);
        }
        return null;
    }

    /**
     * Método para generar la URL data para mostrar en HTML
     */
    public function getImagenQrDataUrlAttribute()
    {
        if ($this->imagen_qr_base64) {
            return 'data:image/png;base64,' . $this->imagen_qr_base64;
        }
        return null;
    }
}