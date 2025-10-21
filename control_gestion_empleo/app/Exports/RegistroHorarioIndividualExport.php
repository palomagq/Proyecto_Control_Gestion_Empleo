<?php

namespace App\Exports;

use App\Models\Empleado;
use App\Models\RegistroTiempo;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log; 


class RegistroHorarioIndividualExport
{
    protected $empleado;
    protected $mes;
    protected $año;

    public function __construct($empleadoId, $mes, $año)
    {
        $this->empleado = Empleado::with(['credencial', 'registrosTiempo'])->find($empleadoId);
        $this->mes = $mes;
        $this->año = $año;
    }

    public function download()
    {
        try {
            if (!$this->empleado) {
                throw new \Exception('Empleado no encontrado');
            }

            // Obtener registros del mes usando la relación
            $registros = $this->empleado->registrosTiempo()
                ->whereYear('created_at', $this->año)
                ->whereMonth('created_at', $this->mes)
                ->orderBy('created_at', 'asc')
                ->get();

            // Preparar datos
            $data = $this->prepararDatos($registros);
            
            // Generar HTML
            $html = $this->generarHtmlParaPdf($data);
            
            // Configurar DomPDF
            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $options->set('dpi', 150);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $nombreArchivo = $this->getFileName();
            
            Log::info('✅ PDF individual generado exitosamente: ' . $nombreArchivo);

            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
                'Pragma' => 'no-cache'
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error en RegistroHorarioIndividualExport: ' . $e->getMessage());
            throw $e;
        }
    }

    private function prepararDatos($registros)
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        // Calcular totales
        $totalHoras = 0;
        $totalComplementariasPactadas = 0;
        $totalComplementariasVoluntarias = 0;

        foreach ($registros as $registro) {
            if ($registro->tiempo_total) {
                $totalHoras += $registro->tiempo_total;
            }
        }

        $totalHorasFormateado = $this->formatearHoras($totalHoras);

        return [
            'empleado' => $this->empleado,
            'registros' => $registros,
            'mes' => $meses[$this->mes] ?? $this->mes,
            'año' => $this->año,
            'total_horas' => $totalHorasFormateado,
            'total_complementarias_pactadas' => $totalComplementariasPactadas,
            'total_complementarias_voluntarias' => $totalComplementariasVoluntarias,
            'fecha_generacion' => Carbon::now()->format('d/m/Y'),
            'dias_mes' => $this->getDiasDelMes()
        ];
    }

    private function generarHtmlParaPdf($data)
    {
        $filasRegistros = $this->generarFilasRegistros($data['registros'], $data['dias_mes']);

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <title>Registro Horario - {$data['empleado']->nombre} {$data['empleado']->apellidos} - {$data['mes']} {$data['año']}</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 0;
                    padding: 15px;
                    font-size: 10px;
                    color: #000;
                    line-height: 1.2;
                }
                
                .header { 
                    text-align: center; 
                    margin-bottom: 15px;
                }
                
                .header h1 { 
                    margin: 0 0 5px 0; 
                    font-size: 14px; 
                    font-weight: bold;
                    text-decoration: underline;
                }
                
                .empresa-info {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 15px;
                    font-size: 9px;
                }
                
                .empresa-info td {
                    padding: 4px 8px;
                    border: 1px solid #000;
                    vertical-align: top;
                }
                
                .empresa-info .label {
                    font-weight: bold;
                    background: #f0f0f0;
                    width: 20%;
                }
                
                .table-registro {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 10px 0;
                    font-size: 8px;
                }
                
                .table-registro th {
                    background: #d9d9d9;
                    padding: 6px 3px;
                    border: 1px solid #000;
                    text-align: center;
                    font-weight: bold;
                    font-size: 7px;
                }
                
                .table-registro td {
                    padding: 4px 3px;
                    border: 1px solid #000;
                    text-align: center;
                    vertical-align: middle;
                    height: 25px;
                }
                
                .table-registro .dia-column {
                    font-weight: bold;
                    background: #f0f0f0;
                    width: 4%;
                }
                
                .total-row {
                    background: #e6e6e6;
                    font-weight: bold;
                }
                
                .firma-section {
                    margin-top: 40px;
                    border-top: 1px dashed #bdc3c7;
                    padding-top: 30px;
                }
                
                .firma-container {
                    display: flex;
                    justify-content: space-between;
                    gap: 20px;
                }
                
                .firma-box {
                    flex: 1;
                    min-width: 0;
                    text-align: center;
                }
                
                .firma-line {
                    border-bottom: 2px solid #7f8c8d;
                    height: 60px;
                    margin-bottom: 10px;
                    position: relative;
                }
                
                .firma-label {
                    margin-top: 8px;
                    font-size: 14px;
                    color: #2c3e50;
                    font-weight: 500;
                }
                
                .firma-note {
                    font-size: 12px;
                    color: #7f8c8d;
                    margin-top: 5px;
                }
                
                .fecha-emision {
                    text-align: right;
                    margin-top: 20px;
                    font-size: 9px;
                }

                    /* Estilos para asegurar que estén en la misma fila */
                    .firma-container {
                        display: flex;
                        flex-direction: row;
                    }
                    
                    .firma-box {
                        width: 40%;
                    }
                    
                    @media (max-width: 600px) {
                        .firma-container {
                            flex-direction: column;
                        }
                        
                        .firma-box {
                            width: 100%;
                            margin-bottom: 30px;
                        }
                    }
            </style>
        </head>
        <body>
            <!-- Encabezado -->
            <div class='header'>
                <h1>LISTADO RESUMEN MENSUAL DEL REGISTRO DE JORNADA (COMPLETO)</h1>
            </div>

            <!-- Información Empresa y Trabajador -->
            <table class='empresa-info'>
                <tr>
                    <td class='label'>Empresa:</td>
                    <td>[NOMBRE DE LA EMPRESA]</td>
                    <td class='label'>Trabajador:</td>
                    <td>{$data['empleado']->nombre} {$data['empleado']->apellidos}</td>
                </tr>
                <tr>
                    <td class='label'>C.I.F./N.I.F.:</td>
                    <td>[CIF EMPRESA]</td>
                    <td class='label'>N.I.F.:</td>
                    <td>{$data['empleado']->dni}</td>
                </tr>
                <tr>
                    <td class='label'>Centro Trabajo:</td>
                    <td>[CENTRO DE TRABAJO]</td>
                    <td class='label'>Nº Afiliación:</td>
                    <td>[Nº AFILIACIÓN]</td>
                </tr>
                <tr>
                    <td class='label'>C.C.C.:</td>
                    <td>[CÓDIGO CUENTA COTIZACIÓN]</td>
                    <td class='label'>Mes y Año:</td>
                    <td>{$data['mes']} {$data['año']}</td>
                </tr>
            </table>

            <!-- Tabla de Registro Horario -->
            <table class='table-registro'>
                <thead>
                    <tr>
                        <th rowspan='2'>DÍA</th>
                        <th colspan='2'>HORARIO</th>
                        <th rowspan='2'>HORAS ORDINARIAS</th>
                        <th colspan='2'>HORAS COMPLEMENTARIAS</th>
                    </tr>
                    <tr>
                        <th>HORA ENTRADA</th>
                        <th>HORA SALIDA</th>
                        <th>PACTADAS</th>
                        <th>VOLUNTARIAS</th>
                    </tr>
                </thead>
                <tbody>
                    {$filasRegistros}
                    <!-- Fila de totales -->
                    <tr class='total-row'>
                        <td>TOTAL</td>
                        <td colspan='2'></td>
                        <td>{$data['total_horas']}</td>
                        <td>{$data['total_complementarias_pactadas']}</td>
                        <td>{$data['total_complementarias_voluntarias']}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Sección de firmas -->
            <div class='firma-section'>
                <div class='firma-container'>
                   <div class='firma-box'>
                        <div class='firma-line'></div>
                        <div class='firma-label'>Firma y sello de la empresa</div>
                        <div class='firma-note'>Nombre y cargo</div>
                    </div>
                    <div class='firma-box'>
                        <div class='firma-line'></div>
                        <div class='firma-label'>Firma del trabajador</div>
                        <div class='firma-note'>Nombre y DNI</div>
                    </div>
                </div>
            </div>

            <!-- Fecha de emisión -->
            <div class='fecha-emision'>
                <p>Documento generado el: {$data['fecha_generacion']}</p>
            </div>
        </body>
        </html>
        ";
    }

    private function generarFilasRegistros($registros, $diasMes)
    {
        $filas = '';
        
        // Generar filas para todos los días del mes
        for ($dia = 1; $dia <= $diasMes; $dia++) {
            $registroDia = $registros->first(function($registro) use ($dia) {
                return $registro->created_at->day == $dia;
            });

            $horaEntrada = $registroDia ? $registroDia->inicio->format('H:i') : '';
            $horaSalida = $registroDia ? ($registroDia->fin ? $registroDia->fin->format('H:i') : '') : '';
            $horasOrdinarias = $registroDia ? $this->formatearHoras($registroDia->tiempo_total) : '';
            
            $filas .= "
            <tr>
                <td class='dia-column'>{$dia}</td>
                <td>{$horaEntrada}</td>
                <td>{$horaSalida}</td>
                <td>{$horasOrdinarias}</td>
                <td></td>
                <td></td>
            </tr>
            ";
        }

        return $filas;
    }

    private function formatearHoras($segundos)
    {
        if (!$segundos) return '';
        
        $horas = floor($segundos / 3600);
        $minutos = floor(($segundos % 3600) / 60);
        
        return sprintf("%dh %02dm", $horas, $minutos);
    }

    private function getDiasDelMes()
    {
        return Carbon::create($this->año, $this->mes, 1)->daysInMonth;
    }

    protected function getFileName()
    {
        $mesNombre = strtolower($this->getNombreMes($this->mes));
        $nombreEmpleado = str_replace(' ', '_', "{$this->empleado->nombre}_{$this->empleado->apellidos}");
        return "registro_horario_{$nombreEmpleado}_{$mesNombre}_{$this->año}.pdf";
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