<!-- Modal Reprogramar -->
<div class="modal fade" id="reprogramarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reprogramar Cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formReprogramar" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    @if(isset($cita))
                    <input type="hidden" name="cita_id" value="{{ $cita->id }}">
                    
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Nueva Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" 
                               value="{{ $cita->fecha ? $cita->fecha->format('Y-m-d') : '' }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="hora_inicio" class="form-label">Nueva Hora Inicio</label>
                        <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" 
                               value="{{ $cita->hora_inicio ? \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') : '' }}" required>
                    </div>

                    <!-- Info actual -->
                    <div class="mt-3 small text-muted">
                        <p>Cita actual: {{ $cita->fecha ? $cita->fecha->format('d/m/Y') : 'N/A' }} 
                           {{ $cita->hora_inicio ? \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') : 'N/A' }}</p>
                        <p>Cliente: {{ $cita->cliente->nombre ?? 'N/A' }}<br>
                           Profesional: {{ $cita->profesional->nombre ?? 'N/A' }}<br>
                           Servicio: {{ $cita->servicio->nombre ?? 'N/A' }}</p>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>