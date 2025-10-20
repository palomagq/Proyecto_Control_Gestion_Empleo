<?php

namespace App\Exports;

use App\Models\Empleado;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;

class EmpleadosPdfExport
{
    protected $mes;
    protected $año;

    public function __construct($mes, $año)
    {
        $this->mes = $mes;
        $this->año = $año;
    }

    public function download()
    {
        try {
            logger('📤 Generando documento PDF real para descarga');

            // Obtener empleados
            $empleados = Empleado::with(['credencial', 'qr'])
                ->whereYear('created_at', $this->año)
                ->whereMonth('created_at', $this->mes)
                ->orderBy('id', 'asc')
                ->get();

            if ($empleados->count() === 0) {
                throw new \Exception('No hay empleados registrados en ' . $this->getNombreMes($this->mes) . ' de ' . $this->año);
            }

            // Preparar datos
            $data = $this->prepararDatos($empleados);
            
            // Generar HTML
            $html = $this->generarHtmlParaPdf($data);
            
            // Configurar DomPDF
            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $options->set('dpi', 150);

            $dompdf = new Dompdf($options);
            
            // Cargar HTML
            $dompdf->loadHtml($html);
            
            // Configurar papel
            $dompdf->setPaper('A4', 'portrait');
            
            // Renderizar PDF
            $dompdf->render();

            $nombreArchivo = $this->getFileName();
            
            logger('✅ PDF real generado exitosamente: ' . $nombreArchivo);

            // Devolver PDF real para descarga
            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
                'Pragma' => 'no-cache'
            ]);

        } catch (\Exception $e) {
            logger()->error('❌ Error generando PDF real:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function prepararDatos($empleados)
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        $totalEmpleados = $empleados->count();
        $totalEdad = $empleados->sum(function($empleado) {
            return Carbon::parse($empleado->fecha_nacimiento)->age;
        });
        $promedioEdad = $totalEmpleados > 0 ? round($totalEdad / $totalEmpleados, 1) : 0;

        $fechaInicio = Carbon::create($this->año, $this->mes, 1)->startOfMonth();
        $fechaFin = Carbon::create($this->año, $this->mes, 1)->endOfMonth();

        return [
            'empleados' => $empleados,
            'mes' => $meses[$this->mes] ?? $this->mes,
            'año' => $this->año,
            'total_empleados' => $totalEmpleados,
            'promedio_edad' => $promedioEdad,
            'fecha_generacion' => Carbon::now()->format('d/m/Y H:i'),
            'fecha_inicio' => $fechaInicio->format('d/m/Y'),
            'fecha_fin' => $fechaFin->format('d/m/Y')
        ];
    }

    private function generarHtmlParaPdf($data)
    {
        $filas = '';
        
        // Ordenar por ID
        $empleadosOrdenados = $data['empleados']->sortBy('id');
        
        foreach ($empleadosOrdenados as $empleado) {
            $edad = Carbon::parse($empleado->fecha_nacimiento)->age;
            $username = $empleado->credencial->username ?? 'N/A';
            
            $filas .= "
            <tr>
                <td style='text-align: center;'>{$empleado->id}</td>
                <td>{$empleado->dni}</td>
                <td>{$empleado->nombre} {$empleado->apellidos}</td>
                <td style='text-align: center;'>{$edad} años</td>
                <td>{$empleado->telefono}</td>
                <td>{$username}</td>
                <td>{$empleado->created_at->format('d/m/Y H:i')}</td>
            </tr>
            ";
        }

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <title>Registro de Empleados - {$data['mes']} {$data['año']}</title>
            <style>
                /* Estilos optimizados para PDF */
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 0;
                    padding: 15px;
                    font-size: 12px;
                    color: #333;
                    line-height: 1.4;
                }
                
                .header { 
                    text-align: center; 
                    border-bottom: 3px solid #333; 
                    padding-bottom: 15px; 
                    margin-bottom: 20px;
                }
                
                .header h1 { 
                    margin: 0 0 8px 0; 
                    color: #333; 
                    font-size: 22px; 
                    font-weight: bold;
                }
                
                .header h2 { 
                    margin: 5px 0; 
                    color: #666; 
                    font-size: 16px; 
                    font-weight: normal;
                }
                
                .info-section {
                    background: #f8f9fa;
                    padding: 12px;
                    border-radius: 4px;
                    border-left: 4px solid #dc3545;
                    margin-bottom: 15px;
                    font-size: 11px;
                }
                
                .summary-grid {
                    display: table;
                    width: 100%;
                    margin: 15px 0;
                    border-collapse: collapse;
                }
                
                .summary-row {
                    display: table-row;
                }
                
                .summary-item {
                    display: table-cell;
                    width: 33.33%;
                    background: white;
                    padding: 12px;
                    text-align: center;
                    border: 1px solid #dee2e6;
                    vertical-align: middle;
                }
                
                .summary-number {
                    font-size: 20px;
                    font-weight: bold;
                    color: #dc3545;
                    display: block;
                }
                
                .summary-label {
                    font-size: 11px;
                    color: #666;
                    margin-top: 5px;
                    display: block;
                }
                
                .table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 15px 0;
                    font-size: 10px;
                    page-break-inside: auto;
                }
                
                .table th {
                    background: #343a40;
                    color: white;
                    padding: 8px 6px;
                    text-align: left;
                    border: 1px solid #dee2e6;
                    font-weight: bold;
                    font-size: 10px;
                }
                
                .table td {
                    padding: 6px;
                    border: 1px solid #dee2e6;
                    vertical-align: top;
                    font-size: 9px;
                }
                
                .table-striped tbody tr:nth-child(odd) {
                    background: #f8f9fa;
                }
                
                .footer {
                    margin-top: 20px;
                    padding-top: 12px;
                    border-top: 2px solid #333;
                    text-align: center;
                    font-size: 9px;
                    color: #666;
                }
                
                .legal-notice {
                    margin-top: 20px;
                    padding: 12px;
                    background: #fff3cd;
                    border: 1px solid #ffeaa7;
                    border-radius: 4px;
                    font-size: 9px;
                }
                
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .text-bold { font-weight: bold; }
                
                /* Mejoras para impresión en PDF */
                @media print {
                    body { 
                        margin: 0;
                        padding: 10px;
                    }
                    .table {
                        font-size: 9px;
                    }
                }
            </style>
        </head>
        <body>
            <!-- Encabezado -->
            <div class='header'>
                <h1>REGISTRO DIGITAL DE CONTROL HORARIO</h1>
                <h2>EMPLEADOS - {$data['mes']} DE {$data['año']}</h2>
                <p>Documento oficial generado el: <strong>{$data['fecha_generacion']}</strong></p>
            </div>

            <!-- Información del período -->
            <div class='info-section'>
                <div style='display: flex; justify-content: space-between; flex-wrap: wrap;'>
                    <div style='flex: 1; min-width: 200px;'><strong>Período:</strong> {$data['fecha_inicio']} al {$data['fecha_fin']}</div>
                    <div style='flex: 1; min-width: 150px;'><strong>Total Empleados:</strong> {$data['total_empleados']}</div>
                    <div style='flex: 1; min-width: 150px;'><strong>Edad Promedio:</strong> {$data['promedio_edad']} años</div>
                </div>
            </div>

            <!-- Resumen Ejecutivo -->
            <div class='summary-grid'>
                <div class='summary-row'>
                    <div class='summary-item'>
                        <span class='summary-number'>{$data['total_empleados']}</span>
                        <span class='summary-label'>TOTAL EMPLEADOS</span>
                    </div>
                    <div class='summary-item'>
                        <span class='summary-number'>{$data['promedio_edad']}</span>
                        <span class='summary-label'>EDAD PROMEDIO</span>
                    </div>
                    <div class='summary-item'>
                        <span class='summary-number'>{$data['año']}</span>
                        <span class='summary-label'>AÑO REGISTRO</span>
                    </div>
                </div>
            </div>

            <!-- Tabla de Empleados -->
            <table class='table table-striped'>
                <thead>
                    <tr>
                        <th width='5%'>ID</th>
                        <th width='12%'>DNI</th>
                        <th width='23%'>Nombre Completo</th>
                        <th width='8%'>Edad</th>
                        <th width='12%'>Teléfono</th>
                        <th width='12%'>Usuario</th>
                        <th width='18%'>Fecha Registro</th>
                    </tr>
                </thead>
                <tbody>
                    {$filas}
                </tbody>
            </table>

            <!-- Información Legal -->
            <div class='legal-notice'>
                <h4 style='margin-top: 0; color: #856404;'>INFORMACIÓN LEGAL</h4>
                <p><strong>Propósito:</strong> Este documento sirve como registro oficial digital de control horario según la legislación laboral vigente.</p>
                <p><strong>Validez:</strong> Documento generado automáticamente con validez legal para inspecciones laborales.</p>
                <p><strong>Periodicidad:</strong> Registro mensual correspondiente al período indicado.</p>
                <p><strong>Uso:</strong> Exclusivo para archivo digital. Conservar según normativa aplicable.</p>
            </div>

            <div class='footer'>
                <p>Documento generado automáticamente por el Sistema de Gestión de Empleados</p>
                <p>Documento de archivo digital | Fecha de generación: {$data['fecha_generacion']}</p>
            </div>
        </body>
        </html>
        ";
    }

    protected function getFileName()
    {
        $mesNombre = strtolower($this->getNombreMes($this->mes));
        return "registro_empleados_{$mesNombre}_{$this->año}.pdf";
    }

    private function getNombreMes($mes)
    {
        $meses = [
            1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
            5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
            9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
        ];
        
        return $meses[$mes] ?? 'mes';
    }
}