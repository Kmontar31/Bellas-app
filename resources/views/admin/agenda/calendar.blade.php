@extends('layouts.admin')

@push('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <style>
        #calendar { min-height: 600px; background: #fff; }
        .fc-event { cursor: pointer; }
        .ocupado { background-color: #dc3545 !important; color: #fff !important; }
        .disponible { background-color: #28a745 !important; color: #fff !important; }
        .bloqueado { background-color: #ffc107 !important; color: #000 !important; }
    </style>
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0">Agenda</h1></div>
                <div class="col-sm-6 text-right">
                    <button class="btn btn-primary btn-sm" onclick="calendar.today()">Hoy</button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="calendar.prev()"><i class="fas fa-chevron-left"></i></button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="calendar.next()"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="card mb-3">
                        <div class="card-header"><strong>Filtros</strong></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Profesional</label>
                                <select id="profesional_filter" class="form-control">
                                    <option value="">Todos</option>
                                    @foreach(\App\Models\Profesional::all() as $profesional)
                                        <option value="{{ $profesional->id }}">{{ $profesional->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Servicio</label>
                                <select id="servicio_filter" class="form-control">
                                    <option value="">Todos</option>
                                    @foreach(\App\Models\Servicio::all() as $servicio)
                                        <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><strong>Leyenda</strong></div>
                        <div class="card-body">
                            <div class="mb-2"><span class="badge bg-success">&nbsp;</span> Disponible</div>
                            <div class="mb-2"><span class="badge bg-danger">&nbsp;</span> Ocupado</div>
                            <div class="mb-2"><span class="badge bg-warning">&nbsp;</span> Bloqueado</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body p-3">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalesContainer"></div>
    @include('components.toast')
    @include('admin.agenda.partials.modal-bloquear')
@endsection

@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js'></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const profesionalFilter = document.getElementById('profesional_filter');
        const servicioFilter = document.getElementById('servicio_filter');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
            locale: 'es',
            selectable: true,
            editable: true,
            events: function(info, successCallback, failureCallback) {
                let url = new URL('{{ route("admin.agenda.events") }}', window.location.origin);
                url.searchParams.append('start', info.startStr);
                url.searchParams.append('end', info.endStr);
                if (profesionalFilter && profesionalFilter.value) url.searchParams.append('profesional_id', profesionalFilter.value);
                if (servicioFilter && servicioFilter.value) url.searchParams.append('servicio_id', servicioFilter.value);
                fetch(url).then(r => r.json()).then(data => successCallback(data)).catch(e => { failureCallback(e); showToast('Error al cargar eventos','text-bg-danger'); });
            },
            eventClick: function(info) { if (info.event.extendedProps.tipo === 'cita') showCitaModal(info.event.id); else if (info.event.extendedProps.tipo === 'bloqueo') {/* mostrar bloqueo */} },
            eventDrop: async function(info) {
                if (info.event.extendedProps && info.event.extendedProps.block) { info.revert(); showToast('No se pueden mover bloqueos','text-bg-warning'); return; }
                try {
                    const resp = await fetch('{{ url("admin/agenda") }}/' + info.event.id + '/reschedule', { method: 'PUT', headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}' }, body: JSON.stringify({ start: info.event.start.toISOString(), end: info.event.end ? info.event.end.toISOString() : null }) });
                    if (!resp.ok) throw new Error('Error');
                    const data = await resp.json(); showToast(data.message,'text-bg-success');
                } catch (e) { info.revert(); showToast('Error al reprogramar','text-bg-danger'); }
            }
        });

        calendar.render(); window.calendar = calendar;

        if (profesionalFilter) profesionalFilter.addEventListener('change', () => calendar.refetchEvents());
        if (servicioFilter) servicioFilter.addEventListener('change', () => calendar.refetchEvents());
    });

    async function loadModal(url) { try { const r = await fetch(url); if (!r.ok) throw new Error('Error'); const html = await r.text(); document.getElementById('modalesContainer').innerHTML = html; const modal = document.getElementById('modalesContainer').querySelector('.modal'); new bootstrap.Modal(modal).show(); return modal; } catch (e) { showToast('Error al cargar modal','text-bg-danger'); } }
    function showCitaModal(id) { return loadModal('{{ url("admin/agenda") }}/' + id + '/modal'); }
    function showToast(message, cls='text-bg-primary') { const t = document.getElementById('liveToast'); if (!t) return; t.className = 'toast ' + cls; t.querySelector('.toast-body').textContent = message; new bootstrap.Toast(t).show(); }
    </script>
@endpush
