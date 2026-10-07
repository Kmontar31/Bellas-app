<div class="modal fade" id="modalCrearCita" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCrearCita">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Cliente</label>
                        <select id="crearCita_cliente" class="form-select" required>
                            <option value="">Seleccione un cliente</option>
                            @foreach(\App\Models\Cliente::all() as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Profesional</label>
                        <select id="crearCita_profesional" class="form-select" required>
                            <option value="">Seleccione un profesional</option>
                            @foreach(\App\Models\Profesional::all() as $prof)
                                <option value="{{ $prof->id }}">{{ $prof->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select id="crearCita_categoria" class="form-select" required>
                            <option value="">Seleccione una categoría</option>
                            @foreach(\App\Models\Categoria::all() as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Servicio</label>
                        <select id="crearCita_servicio" class="form-select" required>
                            <option value="">Seleccione un servicio</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="date" id="crearCita_fecha" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hora Inicio</label>
                        <input type="time" id="crearCita_horaInicio" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select id="crearCita_estado" class="form-select">
                            <option value="pendiente">Pendiente</option>
                            <option value="confirmada">Confirmada</option>
                            <option value="completada">Completada</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notas</label>
                        <textarea id="crearCita_notas" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cita</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formCrearCita = document.getElementById('formCrearCita');
    const categoriaSelect = document.getElementById('crearCita_categoria');
    const servicioSelect = document.getElementById('crearCita_servicio');

    // Cargar servicios cuando cambia la categoría
    if (categoriaSelect) {
        categoriaSelect.addEventListener('change', async function() {
            const categoriaId = this.value;
            if (!categoriaId) {
                servicioSelect.innerHTML = '<option value="">Seleccione un servicio</option>';
                return;
            }

            try {
                const response = await fetch('{{ route("agendar.services") }}?categoria=' + categoriaId);
                const servicios = await response.json();
                servicioSelect.innerHTML = '<option value="">Seleccione un servicio</option>';
                servicios.forEach(servicio => {
                    const option = document.createElement('option');
                    option.value = servicio.id;
                    option.dataset.duracion = servicio.duracion_minutos || 60;
                    option.textContent = servicio.nombre + ' (' + (servicio.duracion_minutos || 60) + ' min)';
                    servicioSelect.appendChild(option);
                });
            } catch (e) {
                console.error('Error loading servicios:', e);
            }
        });
    }

    // Enviar formulario
    if (formCrearCita) {
        formCrearCita.addEventListener('submit', async function(e) {
            e.preventDefault();

            const servicio = document.getElementById('crearCita_servicio');
            const servicioId = servicio.value;
            if (!servicioId) {
                showToast('Debe seleccionar un servicio', 'text-bg-warning');
                return;
            }

            // Obtener duración del servicio
            const servicioOption = servicio.options[servicio.selectedIndex];
            const duracion = servicioOption.dataset.duracion || 60;

            const fecha = document.getElementById('crearCita_fecha').value;
            const horaInicio = document.getElementById('crearCita_horaInicio').value;
            
            // Calcular hora fin
            const start = new Date('2000-01-01 ' + horaInicio);
            const end = new Date(start.getTime() + duracion * 60000);
            const horaFin = end.getHours().toString().padStart(2, '0') + ':' + 
                           end.getMinutes().toString().padStart(2, '0');

            const data = {
                cliente_id: document.getElementById('crearCita_cliente').value,
                profesional_id: document.getElementById('crearCita_profesional').value,
                servicio_id: servicioId,
                fecha: fecha,
                hora_inicio: horaInicio,
                hora_fin: horaFin,
                estado: document.getElementById('crearCita_estado').value,
                notas: document.getElementById('crearCita_notas').value
            };

            try {
                const response = await fetch('/admin/agenda', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    showToast('Cita creada exitosamente', 'text-bg-success');
                    bootstrap.Modal.getInstance(document.getElementById('modalCrearCita')).hide();
                    if (window.calendar) window.calendar.refetchEvents();
                    formCrearCita.reset();
                } else {
                    const error = await response.json();
                    showToast(error.message || 'Error al crear la cita', 'text-bg-danger');
                }
            } catch (e) {
                showToast('Error al crear la cita: ' + e.message, 'text-bg-danger');
            }
        });
    }
});

function openCrearCitaModal(fecha) {
    const modal = new bootstrap.Modal(document.getElementById('modalCrearCita'));
    document.getElementById('crearCita_fecha').value = fecha;
    modal.show();
}
</script>
