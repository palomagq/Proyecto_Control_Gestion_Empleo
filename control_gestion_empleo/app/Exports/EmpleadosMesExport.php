<?php

namespace App\Exports;

use App\Models\Empleado;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class EmpleadosMesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $mes;
    protected $año;

    public function __construct($mes, $año)
    {
        $this->mes = $mes;
        $this->año = $año;
        
        \Log::info('🔄 EmpleadosMesExport construido:', [
            'mes' => $mes, 
            'año' => $año,
            'tipo_mes' => gettype($mes),
            'tipo_año' => gettype($año)
        ]);
    }

    public function collection()
    {
        \Log::info('🔍 EmpleadosMesExport - Buscando colección:', [
            'mes' => $this->mes,
            'año' => $this->año
        ]);

        try {
            // ✅ **Asegurar que son integers**
            $mes = (int) $this->mes;
            $año = (int) $this->año;

            $empleados = Empleado::with('credencial')
                ->whereYear('created_at', $año)
                ->whereMonth('created_at', $mes)
                ->orderBy('created_at', 'desc')
                ->get();

            \Log::info('📋 EmpleadosMesExport - Resultados:', [
                'total' => $empleados->count(),
                'sql' => Empleado::with('credencial')
                    ->whereYear('created_at', $año)
                    ->whereMonth('created_at', $mes)
                    ->orderBy('created_at', 'desc')
                    ->toSql(),
                'empleados' => $empleados->pluck('id')->toArray()
            ]);

            if ($empleados->count() === 0) {
                \Log::warning('🚨 EmpleadosMesExport - Colección vacía');
                // Devolver colección vacía pero no lanzar excepción
                return collect([]);
            }

            return $empleados;

        } catch (\Exception $e) {
            \Log::error('💥 EmpleadosMesExport - Error en collection:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return collect([]);
        }
    }

    public function headings(): array
    {
        return [
            'ID',
            'DNI',
            'NOMBRE COMPLETO',
            'FECHA NACIMIENTO',
            'EDAD',
            'DOMICILIO', 
            'USERNAME',
            'FECHA REGISTRO',
            'COORDENADAS'
        ];
    }

    public function map($empleado): array
    {
        try {
            $edad = $empleado->fecha_nacimiento ? 
                Carbon::parse($empleado->fecha_nacimiento)->age : 'N/A';
            
            $coordenadas = ($empleado->latitud && $empleado->longitud) ? 
                round($empleado->latitud, 6) . ', ' . round($empleado->longitud, 6) : 'No especificadas';
            
            return [
                $empleado->id ?? 'N/A',
                $empleado->dni ?? 'N/A',
                ($empleado->nombre ?? '') . ' ' . ($empleado->apellidos ?? ''),
                $empleado->fecha_nacimiento ? 
                    Carbon::parse($empleado->fecha_nacimiento)->format('d/m/Y') : 'N/A',
                $edad . ' años',
                $empleado->domicilio ?? 'N/A',
                $empleado->credencial->username ?? 'N/A',
                $empleado->created_at ? 
                    $empleado->created_at->format('d/m/Y H:i:s') : 'N/A',
                $coordenadas
            ];
        } catch (\Exception $e) {
            \Log::error('Error mapeando empleado:', [
                'empleado_id' => $empleado->id ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            
            return [
                'ERROR',
                'ERROR',
                'Error procesando datos',
                'N/A',
                'N/A',
                'N/A',
                'N/A',
                'N/A',
                'N/A'
            ];
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4e73df']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ]
        ];
    }

    public function title(): string
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        
        return 'Empleados_' . ($meses[$this->mes] ?? 'Mes') . '_' . $this->año;
    }
}