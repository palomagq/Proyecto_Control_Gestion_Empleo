@extends('empleado.dashboard_empleado')

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <!-- Columna izquierda - Perfil y Estadísticas -->
        <div class="col-lg-4">
            <!-- Tarjeta de Perfil -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user mr-2"></i>Mi Perfil</h5>
                </div>
                <div class="card-body text-center">
                    <!--<div class="profile-avatar">
                        <span class="text-white h3">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>-->
                    <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                    <p class="text-muted mb-3">{{ Auth::user()->email }}</p>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="stats-card">
                                <div class="stats-number">{{ $estadisticasMes['total_registros'] }}</div>
                                <div class="stats-label">Registros</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-card">
                                <div class="stats-number">{{ $estadisticasMes['total_horas'] }}h</div>
                                <div class="stats-label">Horas Totales</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">Promedio diario: {{ $estadisticasMes['promedio_horas'] }}h</small>
                    </div>
                </div>
            </div>

            <!-- Control de Tiempo -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-play-circle mr-2"></i>Control de Tiempo</h5>
                </div>
                <div class="card-body">
                    <div class="time-display">
                        <div id="estado-actual" class="mb-2">Estado: No iniciado</div>
                        <div id="tiempo-transcurrido">00:00:00</div>
                    </div>

                    <div class="text-center">
                        <button id="btn-start" class="btn btn-control btn-start">
                            <i class="fas fa-play mr-2"></i>INICIAR
                        </button>

                        <div id="btn-group-active" style="display: none;">
                            <button id="btn-pause" class="btn btn-control btn-pause">
                                <i class="fas fa-pause mr-2"></i>PAUSAR
                            </button>
                            <button id="btn-stop" class="btn btn-control btn-stop">
                                <i class="fas fa-stop mr-2"></i>DETENER
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna derecha - Historial y Filtros -->
        <div class="col-lg-8">
            <!-- Filtros -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter mr-2"></i>Filtrar por Mes Completo</h5>
                </div>
                <div class="card-body p-2 p-lg-3">
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="filterMes" class="font-weight-bold text-dark small">
                                    <i class="fas fa-calendar-alt mr-1"></i>Seleccione un mes
                                </label>
                                <input type="text" class="form-control" id="filterMes" 
                                    placeholder="Seleccione un mes" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <button id="btn-apply-filters" class="btn btn-primary btn-sm mr-2">
                                    <i class="fas fa-filter mr-1"></i>Filtrar
                                </button>
                                <button id="btn-reset-filters" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-redo mr-1"></i>Mes Actual
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <small class="text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Se filtrará del día 1 al último día del mes
                            </small>
                        </div>
                    </div>

                    <!-- Información del filtro aplicado -->
                    <div class="row mt-2" id="filtroInfo" style="display: none;">
                        <div class="col-md-12">
                            <div class="alert alert-info py-2 mb-0 small d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-info-circle"></i> 
                                    Filtrando por mes completo: <strong id="infoMes"></strong>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-info" onclick="limpiarFiltroMes()">
                                    <i class="fas fa-times"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DataTable -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history mr-2"></i>Historial de Registros</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="historial-table" class="table table-hover table-custom">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora Inicio</th>
                                    <th>Hora Fin</th>
                                    <th>Duración</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Los datos se cargarán via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Resumen Estadístico -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar mr-2"></i>Resumen del Período</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h4 id="total-horas-periodo" class="text-primary">0.00h</h4>
                            <small class="text-muted">Horas Totales</small>
                        </div>
                        <div class="col-md-3">
                            <h4 id="total-registros-periodo" class="text-success">0</h4>
                            <small class="text-muted">Total Registros</small>
                        </div>
                        <div class="col-md-3">
                            <h4 id="promedio-diario-periodo" class="text-info">0.00h</h4>
                            <small class="text-muted">Promedio Diario</small>
                        </div>
                        <div class="col-md-3">
                            <h4 id="dias-trabajados-periodo" class="text-warning">0</h4>
                            <small class="text-muted">Días Trabajados</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')
<!-- Modal de confirmación para STOP -->
<div class="modal fade" id="confirmStopModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Detención</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-stop-circle fa-3x text-danger mb-3"></i>
                <h5>¿Estás seguro de que deseas detener el tiempo?</h5>
                <p class="mb-0">Tiempo transcurrido: <strong id="tiempo-final"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirm-stop">Sí, Detener</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    const empleadoId = {{ $empleado->id }};
    let dataTable;

   // Inicializar Flatpickr para selector de mes
    const flatpickrInstance = flatpickr("#filterMes", {
        plugins: [
            new monthSelectPlugin({
                shorthand: true,
                dateFormat: "Y-m",
                altFormat: "F Y",
                theme: "material_blue"
            })
        ],
        locale: "es",
        onChange: function(selectedDates, dateStr, instance) {
            if (dateStr) {
                $('#btn-apply-filters').prop('disabled', false);
                mostrarInfoFiltro(dateStr);
            } else {
                $('#btn-apply-filters').prop('disabled', true);
                $('#filtroInfo').hide();
            }
        }
    });

    // Inicializar DataTable con manejo de estado vacío
function initializeDataTable() {
    dataTable = $('#historial-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: `/empleado/registro/${empleadoId}/datatable`,
            type: 'GET',
            data: function (d) {
                const selectedDate = $('#filter-month-year').val();
                if (selectedDate) {
                    // Convertir formato YYYY-MM a mes y año separados
                    const partes = selectedDate.split('-');
                    d.year = parseInt(partes[0]);
                    d.month = parseInt(partes[1]);
                } else {
                    // Por defecto, mes actual
                    const now = new Date();
                    d.month = now.getMonth() + 1;
                    d.year = now.getFullYear();
                }
            },
            error: function(xhr, error, thrown) {
                console.error('Error DataTable:', xhr.responseText);
                if (xhr.status === 200) return;
                Swal.fire({
                    icon: 'error',
                    title: 'Error al cargar datos',
                    text: 'No se pudieron cargar los registros.',
                    timer: 3000
                });
            }
        },
        columns: [
            { 
                data: 'created_at',
                name: 'created_at',
                render: function(data) {
                    return data ? new Date(data).toLocaleDateString('es-ES', {
                        weekday: 'short',
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    }) : '-';
                }
            },
            { 
                data: 'inicio',
                name: 'inicio',
                render: function(data) {
                    return data ? new Date(data).toLocaleTimeString('es-ES') : '-';
                }
            },
            { 
                data: 'fin',
                name: 'fin',
                render: function(data) {
                    return data ? new Date(data).toLocaleTimeString('es-ES') : 'En progreso';
                }
            },
            { 
                data: 'tiempo_total',
                name: 'tiempo_total',
                render: function(data) {
                    if (!data) return '-';
                    const hours = Math.floor(data / 3600);
                    const minutes = Math.floor((data % 3600) / 60);
                    const seconds = data % 60;
                    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                }
            },
            { 
                data: 'estado',
                name: 'estado',
                render: function(data) {
                    const statusMap = {
                        'activo': 'badge-active',
                        'pausado': 'badge-paused',
                        'completado': 'badge-completed'
                    };
                    const statusText = data ? data.charAt(0).toUpperCase() + data.slice(1) : 'Desconocido';
                    return `<span class="badge badge-status ${statusMap[data] || 'badge-secondary'}">${statusText}</span>`;
                }
            },
            {
                data: 'id',
                name: 'actions',
                render: function(data) {
                    return data ? `
                        <button class="btn btn-sm btn-outline-primary" onclick="viewDetails(${data})" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                    ` : '';
                },
                orderable: false,
                searchable: false
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json',
            emptyTable: 'No hay registros de tiempo para el mes seleccionado',
            zeroRecords: 'No se encontraron registros que coincidan con el filtro'
        },
        order: [[0, 'desc']],
        drawCallback: function(settings) {
            updatePeriodSummary();
            
            // Mostrar mensaje amigable si no hay datos
            if (settings.json && settings.json.recordsTotal === 0) {
                const api = this.api();
                const $table = $(api.table().node());
                const selectedDate = $('#filter-month-year').val();
                const periodText = selectedDate ? `para ${formatMonthYear(selectedDate)}` : 'para el período seleccionado';
                
                $table.find('.dataTables_empty').html(
                    '<div class="text-center py-4">' +
                    '<i class="fas fa-clock fa-3x text-muted mb-3"></i>' +
                    `<h5 class="text-muted">No hay registros ${periodText}</h5>` +
                    '<p class="text-muted">Cuando trabajes durante este mes, aparecerán aquí tus registros.</p>' +
                    '</div>'
                );
            }
        }
    });
}

    // Aplicar filtros
    $('#btn-apply-filters').click(function() {
        const selectedDate = $('#filterMes').val();
        if (!selectedDate) {
            Swal.fire({
                icon: 'warning',
                title: 'Selecciona un mes',
                text: 'Por favor, selecciona un mes y año para filtrar.',
                timer: 3000
            });
            return;
        }
        
        dataTable.ajax.reload();
        
        const filterBtn = $(this);
        const originalText = filterBtn.html();
        filterBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i> FILTRANDO...');
        
        setTimeout(() => {
            filterBtn.html(originalText);
        }, 1000);
    });


    // Resetear filtros
    $('#btn-reset-filters').click(function() {
        flatpickrInstance.setDate('today');
        dataTable.ajax.reload();
        $('#filtroInfo').hide();
        
        const resetBtn = $(this);
        const originalText = resetBtn.html();
        resetBtn.html('<i class="fas fa-check mr-2"></i> ACTUAL');
        
        setTimeout(() => {
            resetBtn.html(originalText);
        }, 2000);
    });

    // Mostrar información del filtro aplicado
    function mostrarInfoFiltro(fecha) {
        const filtroInfo = $('#filtroInfo');
        const infoMes = $('#infoMes');
        
        if (!fecha || fecha.trim() === '') {
            filtroInfo.hide();
            return;
        }
        
        const partes = fecha.split('-');
        const año = partes[0];
        const mesNumero = parseInt(partes[1]);
        
        const meses = {
            1: 'enero', 2: 'febrero', 3: 'marzo', 4: 'abril',
            5: 'mayo', 6: 'junio', 7: 'julio', 8: 'agosto',
            9: 'septiembre', 10: 'octubre', 11: 'noviembre', 12: 'diciembre'
        };
        
        if (año && mesNumero && meses[mesNumero]) {
            const mesFormateado = `${meses[mesNumero]} de ${año}`;
            infoMes.text(mesFormateado);
            filtroInfo.show();
        }
    }


    // Limpiar filtro de mes
    function limpiarFiltroMes() {
        flatpickrInstance.clear();
        $('#filtroInfo').hide();
        dataTable.ajax.reload();
    }

    // Formatear mes y año para mostrar
    function formatMonthYear(dateString) {
        const partes = dateString.split('-');
        const año = partes[0];
        const mesNumero = parseInt(partes[1]);
        
        const meses = {
            1: 'enero', 2: 'febrero', 3: 'marzo', 4: 'abril',
            5: 'mayo', 6: 'junio', 7: 'julio', 8: 'agosto',
            9: 'septiembre', 10: 'octubre', 11: 'noviembre', 12: 'diciembre'
        };
        
        return meses[mesNumero] ? `${meses[mesNumero]} de ${año}` : dateString;
    }

    // Actualizar resumen del período
    function updatePeriodSummary() {
        const selectedDate = $('#filter-month-year').val();
        let month = null;
        let year = null;

        if (selectedDate) {
            const partes = selectedDate.split('-');
            year = parseInt(partes[0]);
            month = parseInt(partes[1]);
        } else {
            const now = new Date();
            month = now.getMonth() + 1;
            year = now.getFullYear();
        }

        $.ajax({
            url: `/empleado/registro/${empleadoId}/resumen-periodo`,
            method: 'GET',
            data: {
                month: month,
                year: year
            },
            success: function(response) {
                $('#total-horas-periodo').text(response.total_horas + 'h');
                $('#total-registros-periodo').text(response.total_registros);
                $('#promedio-diario-periodo').text(response.promedio_diario + 'h');
                $('#dias-trabajados-periodo').text(response.dias_trabajados);
                
                // Actualizar el título del resumen con el período
                const periodTitle = selectedDate ? 
                    `Resumen de ${formatMonthYear(selectedDate)}` : 
                    'Resumen del Mes Actual';
                    
                // Actualizar el título de la tarjeta de resumen
                $('.card-header h5').last().html(`<i class="fas fa-chart-bar mr-2"></i>${periodTitle}`);
            },
            error: function(xhr) {
                console.error('Error al cargar resumen:', xhr);
            }
        });
    }

    // Control de tiempo (mantener tu lógica existente)
    const btnStart = $('#btn-start');
    const btnPause = $('#btn-pause');
    const btnStop = $('#btn-stop');
    const btnGroupActive = $('#btn-group-active');
    const estadoActual = $('#estado-actual');
    const tiempoTranscurridoElement = $('#tiempo-transcurrido');

    // Verificar estado al cargar la página
    checkEstado();

    // Evento START
    btnStart.click(function() {
        console.log('Iniciando tiempo para empleado:', empleadoId);
        
        $.ajax({
            url: `/empleado/registro/${empleadoId}/start`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('Respuesta START:', response);
                if (response.success) {
                    btnStart.hide();
                    btnGroupActive.show();
                    estadoActual.text('Estado: Activo');
                    checkEstado();
                    loadHistorial();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Tiempo iniciado',
                        text: 'El control de tiempo ha comenzado',
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                console.error('Error START:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo iniciar el tiempo'
                });
            }
        });
    });

    // Evento PAUSE
    btnPause.click(function() {
        // Implementar lógica de pausa aquí
        Swal.fire({
            icon: 'info',
            title: 'Funcionalidad en desarrollo',
            text: 'La función de pausa estará disponible pronto'
        });
    });

    // Evento STOP
    btnStop.click(function() {
        $('#confirmStopModal').modal('show');
    });

    // Confirmar STOP
    $('#confirm-stop').click(function() {
        $.ajax({
            url: `/empleado/registro/${empleadoId}/stop`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                tiempo_total: 3600 // Ejemplo - ajustar según necesidad
            },
            success: function(response) {
                if (response.success) {
                    btnStart.show();
                    btnGroupActive.hide();
                    estadoActual.text('Estado: No iniciado');
                    tiempoTranscurridoElement.text('Tiempo: 00:00:00');
                    loadHistorial();
                    
                    $('#confirmStopModal').modal('hide');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Tiempo detenido',
                        text: response.message,
                        timer: 2000
                    });
                }
            }
        });
    });

    // Función para verificar estado
    function checkEstado() {
        $.ajax({
            url: `/empleado/registro/${empleadoId}/estado`,
            method: 'GET',
            success: function(response) {
                console.log('Estado actual:', response);
                if (response.activo) {
                    btnStart.hide();
                    btnGroupActive.show();
                    estadoActual.text(`Estado: ${response.estado.charAt(0).toUpperCase() + response.estado.slice(1)}`);
                    
                    if (response.tiempo_formateado) {
                        tiempoTranscurridoElement.text(`Tiempo: ${response.tiempo_formateado}`);
                    }
                } else {
                    btnStart.show();
                    btnGroupActive.hide();
                    estadoActual.text('Estado: No iniciado');
                    tiempoTranscurridoElement.text('Tiempo: 00:00:00');
                }
            },
            error: function(xhr) {
                console.error('Error al verificar estado:', xhr);
            }
        });
    }

    // Función para cargar historial
    function loadHistorial() {
        $.ajax({
            url: `/empleado/registro/${empleadoId}/historial`,
            method: 'GET',
            success: function(response) {
                console.log('Historial cargado:', response);
                if (response.success) {
                    $('#historial-hoy').html('');
                    
                    if (response.historial && response.historial.length > 0) {
                        response.historial.forEach(registro => {
                            const inicio = new Date(registro.inicio).toLocaleTimeString();
                            const fin = registro.fin ? new Date(registro.fin).toLocaleTimeString() : null;
                            const tiempoTotal = registro.tiempo_total ? formatTime(registro.tiempo_total) : null;
                            
                            $('#historial-hoy').append(`
                                <div class="historial-item d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>
                                        <strong>Inicio:</strong> ${inicio}
                                        ${fin ? `<strong class="ml-3">Fin:</strong> ${fin}` : '<span class="badge badge-warning ml-3">En progreso</span>'}
                                    </div>
                                    <div>
                                        ${tiempoTotal ? `<span class="badge badge-info">${tiempoTotal}</span>` : ''}
                                    </div>
                                </div>
                            `);
                        });
                    } else {
                        $('#historial-hoy').html('<p class="text-muted text-center">No hay registros para hoy.</p>');
                    }
                }
            },
            error: function(xhr) {
                console.error('Error al cargar historial:', xhr);
            }
        });
    }

    function formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }

    // Inicializar
    initializeDataTable();
    checkEstado();
    loadHistorial();

    // Función para ver detalles
    window.viewDetails = function(registroId) {
        Swal.fire({
            title: 'Detalles del Registro',
            html: 'Aquí puedes mostrar más detalles del registro...',
            icon: 'info',
            confirmButtonText: 'Cerrar'
        });
    };
});
</script>
@endsection

@section('styles')
<style>
:root {
    --primary: #4361ee;
    --secondary: #3f37c9;
    --success: #4cc9f0;
    --danger: #f72585;
    --warning: #f8961e;
    --info: #4895ef;
    --light: #f8f9fa;
    --dark: #212529;
    --sidebar-bg: #2c3e50;
    --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card {
    border: none;
    border-radius: 15px;
    box-shadow: var(--card-shadow);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 20px;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.card-header {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border-radius: 15px 15px 0 0 !important;
    border: none;
    font-weight: 600;
    padding: 15px 20px;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
}

.stats-card {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    margin-bottom: 15px;
}

.stats-number {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.stats-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.btn-control {
    padding: 15px 30px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 50px;
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    margin: 5px;
}

.btn-start {
    background: linear-gradient(135deg, #00b09b, #96c93d);
    color: white;
}

.btn-pause {
    background: linear-gradient(135deg, #ff9a00, #ff6a00);
    color: white;
}

.btn-stop {
    background: linear-gradient(135deg, #ff416c, #ff4b2b);
    color: white;
}

.btn-control:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

.time-display {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    margin: 20px 0;
}

#tiempo-transcurrido {
    font-size: 2.5rem;
    font-weight: bold;
    font-family: 'Courier New', monospace;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.filter-section {
    background: var(--light);
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
}

.table-custom {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--card-shadow);
}

.badge-status {
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 600;
}

.badge-active {
    background: linear-gradient(135deg, #00b09b, #96c93d);
    color: white;
}

.badge-paused {
    background: linear-gradient(135deg, #ff9a00, #ff6a00);
    color: white;
}

.badge-completed {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

/* Agrega esto a tus estilos */
.dataTables_empty {
    padding: 40px !important;
    text-align: center !important;
}

.no-data-container {
    text-align: center;
    padding: 40px;
    color: #6c757d;
}

.no-data-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}


/* Estilos para el selector de mes */
.flatpickr-monthSelect-months {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 5px;
    padding: 10px;
}

.flatpickr-monthSelect-month {
    padding: 10px;
    border-radius: 5px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.flatpickr-monthSelect-month:hover {
    background: #4361ee;
    color: white;
}

.flatpickr-monthSelect-month.selected {
    background: #4361ee;
    color: white;
}

/* Mejorar la apariencia del input */
#filter-month-year {
    background-color: white;
    cursor: pointer;
    font-weight: 500;
}

#filter-month-year:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
}

</style>
@endsection