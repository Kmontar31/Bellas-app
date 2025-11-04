<!-- Modal Vista Detalle -->
<div class="modal fade" id="citaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if(isset($cita))
                <dl class="row">
                    <dt class="col-sm-4">Cliente:</dt>
                    <dd class="col-sm-8">{{ $cita->cliente->nombre ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Email:</dt>
                    <dd class="col-sm-8">{{ $cita->cliente->email ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Teléfono:</dt>
                    <dd class="col-sm-8">{{ $cita->cliente->telefono ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Profesional:</dt>
                    <dd class="col-sm-8">{{ $cita->profesional->nombre ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Servicio:</dt>
                    <dd class="col-sm-8">{{ $cita->servicio->nombre ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Fecha:</dt>
                    <dd class="col-sm-8">{{ $cita->fecha ? $cita->fecha->format('d/m/Y') : 'N/A' }}</dd>

                    <dt class="col-sm-4">Hora inicio:</dt>
                    <dd class="col-sm-8">{{ $cita->hora_inicio ? \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') : 'N/A' }}</dd>

                    <dt class="col-sm-4">Hora fin:</dt>
                    <dd class="col-sm-8">{{ $cita->hora_fin ? \Carbon\Carbon::parse($cita->hora_fin)->format('H:i') : 'N/A' }}</dd>

                    <dt class="col-sm-4">Estado:</dt>
                    <dd class="col-sm-8">{{ $cita->estado ?? 'N/A' }}</dd>
                </dl>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="showReprogramarModal({{ $cita->id ?? 'null' }})">Reprogramar</button>
            </div>
        </div>
    </div>
</div>