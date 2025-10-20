<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registro Digital de Empleados - {{ $mes }} {{ $año }}</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 11px; 
            line-height: 1.3; 
            margin: 15px;
        }
        
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #333; 
            padding-bottom: 10px; 
        }
        
        .header h1 { 
            margin: 0; 
            color: #333; 
            font-size: 18px; 
            font-weight: bold;
        }
        
        .header h2 { 
            margin: 5px 0; 
            color: #666; 
            font-size: 14px; 
        }
        
        .info-empresa { 
            margin-bottom: 15px; 
            padding: 10px; 
            background: #f8f9fa; 
            border-radius: 5px; 
            border-left: 4px solid #dc3545;
        }
        
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px; 
            font-size: 9px;
        }
        
        .table th { 
            background: #343a40; 
            color: white; 
            padding: 6px 4px; 
            text-align: left; 
            border: 1px solid #dee2e6; 
            font-weight: bold;
        }
        
        .table td { 
            padding: 5px 4px; 
            border: 1px solid #dee2e6; 
            vertical-align: top;
        }
        
        .table-striped tbody tr:nth-child(odd) { 
            background: #f8f9fa; 
        }
        
        .footer { 
            margin-top: 20px; 
            padding-top: 10px; 
            border-top: 1px solid #333; 
            text-align: center; 
            font-size: 8px; 
            color: #666; 
        }
        
        .summary-grid { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 8px; 
            margin: 15px 0; 
        }
        
        .summary-item { 
            background: white; 
            padding: 8px; 
            border-radius: 4px; 
            text-align: center; 
            border: 1px solid #dee2e6;
        }
        
        .summary-number { 
            font-size: 16px; 
            font-weight: bold; 
            color: #dc3545; 
        }
        
        .summary-label { 
            font-size: 9px; 
            color: #666; 
        }
        
        .legal-notice { 
            margin-top: 15px; 
            padding: 10px; 
            background: #fff3cd; 
            border: 1px solid #ffeaa7; 
            border-radius: 4px; 
            font-size: 8px;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .nowrap { white-space: nowrap; }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <h1>REGISTRO DIGITAL DE CONTROL HORARIO</h1>
        <h2>EMPLEADOS - {{ $mes }} DE {{ $año }}</h2>
        <p>Documento oficial generado el: <strong>{{ $fecha_generacion }}</strong></p>
    </div>

    <!-- Información del período -->
    <div class="info-empresa">
        <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <div><strong>Período:</strong> {{ $fecha_inicio }} al {{ $fecha_fin }}</div>
            <div><strong>Total Empleados:</strong> {{ $total_empleados }}</div>
            <div><strong>Edad Promedio:</strong> {{ $promedio_edad }} años</div>
        </div>
    </div>

    <!-- Resumen Ejecutivo -->
    <div class="summary-grid">
        <div class="summary-item">
            <div class="summary-number">{{ $total_empleados }}</div>
            <div class="summary-label">TOTAL EMPLEADOS</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">{{ $promedio_edad }}</div>
            <div class="summary-label">EDAD PROMEDIO</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">{{ $año }}</div>
            <div class="summary-label">AÑO REGISTRO</div>
        </div>
    </div>

    <!-- Tabla de Empleados COMPLETA -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="4%">ID</th>
                <th width="10%">DNI</th>
                <th width="15%">Nombre Completo</th>
                <th width="8%">Fecha Nac.</th>
                <th width="6%">Edad</th>
                <th width="9%">Teléfono</th>
                <th width="20%">Domicilio</th>
                <th width="8%">Usuario</th>
                <th width="10%">Fecha Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($empleados as $empleado)
            <tr>
                <td class="text-center">{{ $empleado->id }}</td>
                <td class="nowrap">{{ $empleado->dni }}</td>
                <td>{{ $empleado->nombre }} {{ $empleado->apellidos }}</td>
                <td class="nowrap">{{ \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m/Y') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($empleado->fecha_nacimiento)->age }} años</td>
                <td class="nowrap">{{ $empleado->telefono }}</td>
                <td>{{ Str::limit($empleado->domicilio, 40) }}</td>
                <td>{{ $empleado->credencial->username ?? 'N/A' }}</td>
                <td class="nowrap">{{ $empleado->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Información Legal -->
    <div class="legal-notice">
        <h4 style="margin-top: 0; color: #856404; font-size: 9px;">INFORMACIÓN LEGAL</h4>
        <p><strong>Propósito:</strong> Este documento sirve como registro oficial digital de control horario según la legislación laboral vigente.</p>
        <p><strong>Validez:</strong> Documento generado automáticamente con validez legal para inspecciones laborales.</p>
        <p><strong>Periodicidad:</strong> Registro mensual correspondiente al período indicado.</p>
    </div>

    <div class="footer">
        <p>Documento generado automáticamente por el Sistema de Gestión de Empleados</p>
        <p>Página 1 de 1 | Fecha de generación: {{ $fecha_generacion }}</p>
    </div>
</body>
</html>