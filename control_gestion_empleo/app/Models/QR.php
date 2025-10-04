<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{
    use HasFactory;

    protected $table = 'tabla_qrs';

    protected $fillable = [
        'imagen_qr',
        'codigo_unico'
    ];

    protected $casts = [
        'imagen_qr' => 'binary' // Esto ayuda con el manejo de datos binarios
    ];

    // Relación con empleados
    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'qr_id');
    }

    // Accesor para obtener la imagen como base64
    public function getImagenQrBase64Attribute()
    {
        return base64_encode($this->imagen_qr);
    }

    // Método para generar la URL data para mostrar en HTML
    public function getImagenQrDataUrlAttribute()
    {
        if ($this->imagen_qr) {
            $mimeType = 'image/png'; // Asumiendo que siempre es PNG
            return 'data:' . $mimeType . ';base64,' . base64_encode($this->imagen_qr);
        }
        return null;
    }
}