<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resumen Empleados - {{ $mes }} {{ $año }}</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 12px; 
            line-height: 1.4; 
            margin: 20px;
        }
        
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #333; 
            padding-bottom: 15px; 
        }
        
        .header h1 { 
            margin: 0; 
            color: #333; 
            font-size: 20px; 
            font-weight: bold;
        }
        
        .header h2 { 
            margin: 8px 0; 
            color: #666; 
            font-size: 16px; 
        }
        
        .summary-grid { 
            display: grid; 
            grid-template-columns: repeat(2, 1fr); 
            gap: 15px; 
            margin: 25px 0; 
        }
        
        .summary-item { 
            background: #f8f9fa; 
            padding: 15px; 
            border-radius: 8px; 
            text-align: center; 
            border: 2px solid #dee2e6;
        }
        
        .summary-number { 
            font-size: 28px; 
            font-weight: bold; 
            color: #dc3545; 
        }
        
        .summary-label { 
            font-size: 14px; 
            color: #666; 
            margin-top: 8px;
        }
        
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0; 
        }
        
        .table th { 
            background: #343a40; 
            color: white; 
            padding: 10px 8px; 
            text-align: left; 
            border: 1px solid #dee2e6; 
            font-weight: bold;
        }
        
        .table td { 
            padding: 8px; 
            border: 1px solid #dee2e6; 
        }
        
        .table-striped tbody tr:nth-child(odd) { 
            background: #f8f9fa; 
        }
        
        .footer { 
            margin-top: 30px; 
            padding-top: 15px; 
            border-top: 1px solid #333; 
            text-align: center; 
            font-size: 10px; 
            color: #666; 
        }
        
        .text-center { text-align: center; }
        .nowrap { white-space: nowrap; }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <h1>RESUMEN EJECUTIVO - EMPLEADOS</h1>
        <h2>{{ $mes }} DE {{ $año }}</h2>
        <p>Documento generado el: <strong>{{ $fecha_generacion }}</strong></p>
    </div>

    <!-- Resumen Estadístico -->
    <div class="summary-grid">
        <div class="summary-item">
            <div class="summary-number">{{ $total_empleados }}</div>
            <div class="summary-label">TOTAL EMPLEADOS REGISTRADOS</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">{{ $promedio_edad }}</div>
            <div class="summary-label">EDAD PROMEDIO</div>
        </div>
    </div>

    <!-- Tabla RESUMEN (más compacta) -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="12%">DNI</th>
                <th width="25%">Nombre Completo</th>
                <th width="8%">Edad</th>
                <th width="15%">Teléfono</th>
                <th width="20%">Usuario</th>
                <th width="20%">Fecha Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($empleados as $empleado)
            <tr>
                <td class="nowrap">{{ $empleado->dni }}</td>
                <td>{{ $empleado->nombre }} {{ $empleado->apellidos }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($empleado->fecha_nacimiento)->age }} años</td>
                <td class="nowrap">{{ $empleado->telefono }}</td>
                <td>{{ $empleado->credencial->username ?? 'N/A' }}</td>
                <td class="nowrap">{{ $empleado->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Resumen ejecutivo generado por el Sistema de Gestión de Empleados</p>
        <p>Período: {{ $fecha_inicio }} al {{ $fecha_fin }} | Fecha: {{ $fecha_generacion }}</p>
    </div>
</body>
</html>