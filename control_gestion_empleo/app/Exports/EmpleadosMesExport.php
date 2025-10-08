<?php

namespace App\Exports;

use App\Models\Empleado;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class EmpleadosMesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $mes;
    protected $año;

    public function __construct($mes, $año)
    {
        $this->mes = (int)$mes;
        $this->año = (int)$año;
    }

    public function collection()
    {
        $fechaInicio = Carbon::create($this->año, $this->mes, 1)->startOfMonth();
        $fechaFin = Carbon::create($this->año, $this->mes, 1)->endOfMonth();

        $empleados = Empleado::with('credencial')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->orderBy('id', 'asc')
            ->get();

        // Log para verificar el orden
        \Log::info('📋 Empleados para exportar (ordenados por ID):', [
            'mes' => $this->mes,
            'año' => $this->año,
            'total' => $empleados->count(),
            'ids' => $empleados->pluck('id')->toArray(),
            'primer_id' => $empleados->first()?->id,
            'ultimo_id' => $empleados->last()?->id
        ]);

        return $empleados;
    }

    public function headings(): array
    {
        return [
            'ID',
            'DNI',
            'Nombre',
            'Apellidos',
            'Fecha Nacimiento',
            'Edad',
            'Teléfono',
            'Domicilio',
            'Username',
            'Fecha Registro',
            'Coordenadas'
        ];
    }

    public function map($empleado): array
    {
        $edad = Carbon::parse($empleado->fecha_nacimiento)->age;
        $coordenadas = $empleado->latitud && $empleado->longitud 
            ? "{$empleado->latitud}, {$empleado->longitud}" 
            : 'No especificadas';

        return [
            $empleado->id,
            $empleado->dni,
            $empleado->nombre,
            $empleado->apellidos,
            Carbon::parse($empleado->fecha_nacimiento)->format('d/m/Y'),
            $edad . ' años',
            $empleado->telefono,
            $empleado->domicilio,
            $empleado->credencial->username ?? 'N/A',
            $empleado->created_at->format('d/m/Y H:i:s'),
            $coordenadas
        ];
    }

    public function title(): string
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        
        return "Empleados {$meses[$this->mes]} {$this->año}";
    }
}