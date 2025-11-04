<!-- Modal Bloquear Fecha -->
<div class="modal fade" id="bloquearModal" tabindex="-1" aria-labelledby="bloquearModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bloquearModalLabel">Bloquear Fecha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formBloquear" action="{{ route('admin.agenda.blocks.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="fecha" id="bloquearFecha">
                    
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Fecha seleccionada</h6>
                            <p class="card-text" id="fechaSeleccionada"></p>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="profesional_id" class="form-label">Profesional</label>
                        <select class="form-select" id="profesional_id" name="profesional_id">
                            <option value="">Todos los profesionales</option>
                            @foreach(App\Models\Profesional::all() as $profesional)
                                <option value="{{ $profesional->id }}">{{ $profesional->nombre }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            Si no selecciona ningún profesional, se bloqueará la fecha para todos
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="motivo" class="form-label">Motivo del Bloqueo</label>
                        <textarea class="form-control" id="motivo" name="motivo" rows="3" 
                                placeholder="Escribe el motivo del bloqueo (opcional)"></textarea>
                        <div class="form-text">
                            El motivo ayudará a recordar por qué se bloqueó esta fecha
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <strong>Importante:</strong>
                        <ul class="mb-0 mt-1">
                            <li>Esta fecha quedará bloqueada para nuevas reservas</li>
                            <li>Las citas existentes deberán ser reprogramadas manualmente</li>
                            <li>El bloqueo se puede eliminar más tarde si es necesario</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Bloquear Fecha</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Asegurarse de incluir Font Awesome si no está ya incluido en el layout -->
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('bloquearModal');
    if (modal) {
        modal.addEventListener('show.bs.modal', function(event) {
            const fecha = document.getElementById('bloquearFecha').value;
            const fechaFormateada = new Date(fecha).toLocaleDateString('es-ES', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('fechaSeleccionada').textContent = fechaFormateada;
        });

        const form = modal.querySelector('#formBloquear');
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            try {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Bloqueando...';
                
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        fecha: document.getElementById('bloquearFecha').value,
                        motivo: document.getElementById('motivo').value
                    })
                });

                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.message || 'Error al bloquear la fecha');
                }

                window.showToast('Fecha bloqueada correctamente', 'text-bg-success');
                bootstrap.Modal.getInstance(modal).hide();
                if (typeof calendar !== 'undefined') {
                    calendar.refetchEvents();
                }
            } catch (error) {
                window.showToast(error.message, 'text-bg-danger');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }
});
</script>
@endpush