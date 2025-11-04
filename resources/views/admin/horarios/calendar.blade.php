@extends('layouts.admin')

@section('styles')
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Administración de Horarios</h1>
            <ul class="nav nav-tabs" id="horariosTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="calendar-tab" data-bs-toggle="tab" href="#calendar-content" role="tab">
                        <i class="fas fa-calendar-alt"></i> Calendario
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="blocks-tab" data-bs-toggle="tab" href="#blocks-content" role="tab">
                        <i class="fas fa-ban"></i> Días Bloqueados
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="horariosTabsContent">
        <!-- Contenido del Calendario -->
        <div class="tab-pane fade show active" id="calendar-content" role="tabpanel">
            <div class="row">
                <!-- Sidebar con lista de profesionales -->
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Profesionales</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action active" data-profesional-id="">
                                    Todos los profesionales
                                </a>
                                @foreach($profesionales as $profesional)
                                    <a href="#" class="list-group-item list-group-item-action" data-profesional-id="{{ $profesional->id }}">
                                        {{ $profesional->nombre }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendario -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido de Días Bloqueados -->
        <div class="tab-pane fade" id="blocks-content" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="bloqueos-table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Profesional</th>
                                    <th>Motivo</th>
                                    <th>Creado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bloqueos as $bloqueo)
                                    <tr>
                                        <td>{{ $bloqueo->fecha->format('d/m/Y') }}</td>
                                        <td>{{ optional($bloqueo->profesional)->nombre ?? 'Todos los profesionales' }}</td>
                                        <td>{{ $bloqueo->motivo ?? '-' }}</td>
                                        <td>{{ $bloqueo->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info me-1 edit-block" 
                                                    data-id="{{ $bloqueo->id }}"
                                                    data-fecha="{{ $bloqueo->fecha->format('Y-m-d') }}"
                                                    data-profesional="{{ optional($bloqueo->profesional)->id }}"
                                                    data-motivo="{{ $bloqueo->motivo }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-block" data-id="{{ $bloqueo->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Bloqueo -->
@include('admin.agenda.partials.modal-bloquear')

@endsection

@section('scripts')
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale/es.js'></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar DataTables
    $('#bloqueos-table').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        order: [[0, 'desc']]
    });

    // Manejar edición de bloqueo
    $('.edit-block').click(function() {
        const data = $(this).data();
        $('#bloquearFecha').val(data.fecha);
        $('#fechaSeleccionada').text(moment(data.fecha).format('LL'));
        $('#profesional_id').val(data.profesional || '');
        $('#motivo').val(data.motivo);
        
        // Cambiar el formulario para actualización
        const form = $('#formBloquear');
        form.attr('action', `{{ route('admin.agenda.blocks.store') }}/${data.id}`);
        form.append('@method("PUT")');
        
        $('#bloquearModalLabel').text('Editar Bloqueo');
        $('#bloquearModal').modal('show');
    });

    // Manejar eliminación de bloqueo
    $('.delete-block').click(function() {
        const id = $(this).data('id');
        if (confirm('¿Está seguro de eliminar este bloqueo?')) {
            fetch(`{{ route('admin.agenda.blocks.store') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Error al eliminar el bloqueo');
                }
            });
        }
    });
    let selectedProfesionalId = '';
    const calendar = $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        locale: 'es',
        navLinks: true,
        selectable: true,
        selectHelper: true,
        select: function(start) {
            const fecha = moment(start).format('YYYY-MM-DD');
            $('#bloquearFecha').val(fecha);
            $('#fechaSeleccionada').text(moment(fecha).format('LL'));
            
            // Pre-seleccionar el profesional si hay uno seleccionado
            if (selectedProfesionalId) {
                $('#profesional_id').val(selectedProfesionalId);
            } else {
                $('#profesional_id').val('');
            }
            
            $('#bloquearModal').modal('show');
        },
        events: function(start, end, timezone, callback) {
            $.ajax({
                url: '{{ route("admin.agenda.events") }}',
                data: {
                    start: start.format('YYYY-MM-DD'),
                    end: end.format('YYYY-MM-DD'),
                    profesional_id: selectedProfesionalId
                },
                success: function(response) {
                    const events = response.map(event => {
                        if (event.extendedProps && event.extendedProps.block) {
                            return {
                                ...event,
                                rendering: 'background',
                                color: '#ff9f89'
                            };
                        }
                        return event;
                    });
                    callback(events);
                }
            });
        }
    });

    // Manejar clicks en la lista de profesionales
    document.querySelectorAll('.list-group-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.list-group-item').forEach(el => el.classList.remove('active'));
            this.classList.add('active');
            selectedProfesionalId = this.dataset.profesionalId;
            calendar.fullCalendar('refetchEvents');
        });
    });
});
</script>
@endsection