<!-- Modal Detalles de Cita -->
<div class="modal fade" id="detallesCitaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Cliente</h6>
                        <p class="mb-3"><strong>{{ $agenda->cliente?->nombre ?? 'N/A' }}</strong></p>

                        <h6 class="text-muted">Email</h6>
                        <p class="mb-3">{{ $agenda->cliente?->email ?? 'N/A' }}</p>

                        <h6 class="text-muted">Teléfono</h6>
                        <p class="mb-3">{{ $agenda->cliente?->telefono ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Profesional</h6>
                        <p class="mb-3"><strong>{{ $agenda->profesional?->nombre ?? 'N/A' }}</strong></p>

                        <h6 class="text-muted">Servicio</h6>
                        <p class="mb-3"><strong>{{ $agenda->servicio?->nombre ?? 'N/A' }}</strong></p>

                        <h6 class="text-muted">Estado</h6>
                        <p class="mb-3">
                            <span class="badge bg-{{ $agenda->estado === 'confirmada' ? 'success' : ($agenda->estado === 'cancelada' ? 'danger' : 'warning') }}">
                                {{ ucfirst($agenda->estado) }}
                            </span>
                        </p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <h6 class="text-muted">Fecha</h6>
                        <p class="mb-3"><strong>{{ $agenda->fecha->format('d/m/Y') }}</strong></p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Hora Inicio</h6>
                        <p class="mb-3"><strong>{{ substr($agenda->hora_inicio, 0, 5) }}</strong></p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Hora Fin</h6>
                        <p class="mb-3"><strong>{{ substr($agenda->hora_fin, 0, 5) }}</strong></p>
                    </div>
                </div>

                @if($agenda->notas)
                    <hr>
                    <h6 class="text-muted">Notas</h6>
                    <p class="mb-3">{{ $agenda->notas }}</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="editarCita({{ $agenda->id }})">Editar</button>
                <button type="button" class="btn btn-danger" onclick="eliminarCita({{ $agenda->id }})">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>
function editarCita(id) {
    // Cargar modal de edición
    console.log('Editar cita ' + id);
}

function eliminarCita(id) {
    if (!confirm('¿Está seguro de que desea eliminar esta cita?')) return;
    
    fetch('/admin/agenda/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(r => {
        if (r.ok) {
            showToast('Cita eliminada correctamente', 'text-bg-success');
            bootstrap.Modal.getInstance(document.getElementById('detallesCitaModal')).hide();
            if (window.calendar) window.calendar.refetchEvents();
        } else {
            showToast('Error al eliminar la cita', 'text-bg-danger');
        }
    }).catch(e => {
        showToast('Error: ' + e.message, 'text-bg-danger');
    });
}
</script>
