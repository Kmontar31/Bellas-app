@extends('layouts.app')

<section id="appointment" class="appointment section py-5">

  <!-- Section Title -->
  <div class="container mb-5">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center">
        <h2 class="display-5 mb-3">Reserva tu Cita</h2>
        <p class="lead text-muted">Selecciona categoría, servicio y profesional para agendar tu cita</p>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
              <i class="bi bi-check-circle me-2"></i>
              <div>
                <strong>¡Éxito!</strong> Tu reserva ha sido registrada.
              </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif
        
        @if($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex">
              <i class="bi bi-exclamation-triangle me-2 flex-shrink-0"></i>
              <div>
                <strong>Por favor revisa los siguientes errores:</strong>
                <ul class="mb-0 mt-2 small">
                  @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                  @endforeach
                </ul>
              </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        <form action="{{ route('agendar.store') }}" method="post" class="needs-validation" novalidate>
          @csrf
          
          <!-- Información Personal -->
          <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-gradient text-white border-0">
              <h3 class="mb-0 d-flex align-items-center">
                <i class="bi bi-person-circle me-2"></i>
                Tu Información
              </h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="name" class="form-label fw-500">Nombre <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" placeholder="Juan Pérez" required value="{{ old('name') }}">
                  @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="email" class="form-label fw-500">Correo Electrónico <span class="text-danger">*</span></label>
                  <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" id="email" placeholder="tu@email.com" required value="{{ old('email') }}">
                  @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
              </div>
              <div class="row">
                <div class="col-12 mb-3">
                  <label for="phone" class="form-label fw-500">Teléfono</label>
                  <input type="tel" class="form-control form-control-lg @error('phone') is-invalid @enderror" name="phone" id="phone" placeholder="+34 600 000 000" value="{{ old('phone') }}">
                  @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
              </div>
            </div>
          </div>

          <!-- Selección de Servicio -->
          <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-gradient text-white border-0">
              <h3 class="mb-0 d-flex align-items-center">
                <i class="bi bi-stars me-2"></i>
                Selecciona el Servicio
              </h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="categoria" class="form-label fw-500">Categoría <span class="text-danger">*</span></label>
                  <select id="categoria" name="categoria" class="form-select form-select-lg @error('categoria') is-invalid @enderror" required>
                    <option value="">-- Selecciona una categoría --</option>
                    @foreach($categories as $cat)
                      <option value="{{ $cat }}" @selected(old('categoria') === $cat)>{{ $cat }}</option>
                    @endforeach
                  </select>
                  @error('categoria')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label for="servicio_id" class="form-label fw-500">Servicio <span class="text-danger">*</span></label>
                  <select id="servicio_id" name="servicio_id" class="form-select form-select-lg @error('servicio_id') is-invalid @enderror" required disabled>
                    <option value="">-- Selecciona categoría primero --</option>
                  </select>
                  @error('servicio_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
              </div>
            </div>
          </div>

          <!-- Horario y Profesional -->
          <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-gradient text-white border-0">
              <h5 class="mb-0 d-flex align-items-center">
                <i class="bi bi-calendar-event me-2"></i>
                Elige tu Horario
              </h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="fecha" class="form-label fw-500">Fecha <span class="text-danger">*</span></label>
                  <input type="date" name="fecha" id="fecha" class="form-control form-control-lg @error('fecha') is-invalid @enderror" required value="{{ old('fecha') }}">
                  @error('fecha')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                  <label for="hora" class="form-label fw-500">Hora <span class="text-danger">*</span></label>
                  <input type="time" name="hora" id="hora" class="form-control form-control-lg @error('hora') is-invalid @enderror" required value="{{ old('hora') }}">
                  @error('hora')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4 mb-3">
                  <label for="profesional_id" class="form-label fw-500">Profesional <span class="text-danger">*</span></label>
                  <select id="profesional_id" name="profesional_id" class="form-select form-select-lg @error('profesional_id') is-invalid @enderror" required>
                    <option value="">-- Selecciona --</option>
                    @foreach($profesionales as $p)
                      <option value="{{ $p->id }}" @selected(old('profesional_id') == $p->id)>{{ $p->nombre }}</option>
                    @endforeach
                  </select>
                  @error('profesional_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
              </div>
            </div>
          </div>

          <!-- Mensaje Adicional -->
          <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-gradient text-white border-0">
              <h5 class="mb-0 d-flex align-items-center">
                <i class="bi bi-chat-dots me-2"></i>
                Mensaje Adicional (Opcional)
              </h5>
            </div>
            <div class="card-body">
              <textarea class="form-control @error('message') is-invalid @enderror" name="message" rows="4" placeholder="Cuéntanos si tienes algún requisito especial o preferencia...">{{ old('message') }}</textarea>
              @error('message')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
          </div>

          <!-- Botón Envío -->
          <div class="d-grid gap-2 mb-4">
            <button id="submitBtn" type="submit" class="btn btn-primary btn-lg" disabled>
              <i class="bi bi-check-circle me-2"></i>
              <span>Reservar Cita</span>
            </button>
          </div>
          
          <p class="text-muted text-center small">
            Los campos marcados con <span class="text-danger fw-bold">*</span> son obligatorios
          </p>
        </form>

      </div>
    </div>
  </div>

</section>

@push('styles')
<style>
  /* Card headers con degradado */
  .card-header.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border-radius: 0.5rem 0.5rem 0 0;
  }

  /* Mejora de visibilidad de labels */
  .form-label {
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
    color: #2c3e50;
  }

  .form-label span {
    font-size: 1rem;
  }

  /* Inputs y selects */
  .form-control-lg,
  .form-select-lg {
    padding: 0.75rem 1rem;
    font-size: 1rem;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }

  /* Botón mejorado */
  #submitBtn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    font-weight: 600;
    letter-spacing: 0.5px;
    padding: 1rem 2rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  }

  #submitBtn:not(:disabled):hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
  }

  #submitBtn:disabled {
    background: linear-gradient(135deg, #ccc 0%, #999 100%);
    cursor: not-allowed;
    opacity: 0.6;
  }

  #submitBtn.btn-danger {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
  }

  /* Cards con sombra suave */
  .card {
    transition: all 0.3s ease;
  }

  .card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
  }

  /* Alertas mejoradas */
  .alert {
    border: none;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
  }

  .alert-success {
    background-color: #d4edda;
    color: #155724;
  }

  .alert-danger {
    background-color: #f8d7da;
    color: #721c24;
  }

  /* Mensaje de error en campos */
  .invalid-feedback {
    font-size: 0.875rem;
    font-weight: 500;
    margin-top: 0.25rem;
  }

  .is-invalid {
    border-color: #dc3545 !important;
  }

  /* Responsive improvements */
  @media (max-width: 768px) {
    .card-body {
      padding: 1.5rem 1rem;
    }

    .form-control-lg,
    .form-select-lg {
      padding: 0.65rem 0.85rem;
      font-size: 0.95rem;
    }

    #submitBtn {
      padding: 0.9rem 1.5rem;
      font-size: 0.95rem;
    }
  }

  /* Section padding */
  .appointment.section {
    background-color: #f8f9fa;
  }

  /* Título de la sección */
  .appointment .display-5 {
    font-weight: 700;
    color: #2c3e50;
  }

  .appointment .lead {
    font-size: 1.1rem;
  }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const categoria = document.getElementById('nombre');
  const servicioSel = document.getElementById('nombre');
  const profesionalSel = document.getElementById('profesional_id');
  const fecha = document.getElementById('fecha');
  const hora = document.getElementById('hora');
  const submitBtn = document.getElementById('submitBtn');
  const form = document.querySelector('form');

  // Set minimum date to today
  const today = new Date().toISOString().split('T')[0];
  fecha.setAttribute('min', today);

  /**
   * Cargar servicios por categoría
   */
  async function loadServices(cat) {
    servicioSel.innerHTML = '<option value="">⏳ Cargando servicios...</option>';
    servicioSel.disabled = true;
    
    try {
      const res = await fetch('{{ route('agendar.services') }}?categoria=' + encodeURIComponent(cat));
      
      if (!res.ok) {
        throw new Error('Error al cargar servicios');
      }
      
      const data = await res.json();
      
      if (!data || data.length === 0) {
        servicioSel.innerHTML = '<option value="">No hay servicios disponibles para esta categoría</option>';
        servicioSel.disabled = true;
        return;
      }
      
      servicioSel.innerHTML = '<option value="">-- Selecciona un servicio --</option>';
      data.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id;
        const duracion = s.duracion_minutos || 60;
        const precio = s.precio ? ` · $${parseFloat(s.precio).toFixed(2)}` : '';
        opt.textContent = `${s.nombre} (${duracion} min)${precio}`;
        servicioSel.appendChild(opt);
      });
      
      servicioSel.disabled = false;
    } catch (e) {
      console.error('Error:', e);
      servicioSel.innerHTML = '<option value="">Error al cargar servicios</option>';
      servicioSel.disabled = true;
    }
  }

  /**
   * Cambio de categoría
   */
  categoria && categoria.addEventListener('change', function() {
    if (this.value) {
      loadServices(this.value);
      servicioSel.value = '';
      submitBtn.disabled = true;
      updateButtonState();
    } else {
      servicioSel.innerHTML = '<option value="">-- Selecciona categoría primero --</option>';
      servicioSel.disabled = true;
      submitBtn.disabled = true;
      updateButtonState();
    }
  });

  /**
   * Verificar disponibilidad del profesional
   */
  async function checkAvailability() {
    // Verificar que todos los campos requeridos estén completos
    if (!profesionalSel.value || !fecha.value || !hora.value || !servicioSel.value) {
      return false;
    }
    
    const params = new URLSearchParams({
      profesional_id: profesionalSel.value,
      fecha: fecha.value,
      hora: hora.value,
      servicio_id: servicioSel.value
    });
    
    try {
      const res = await fetch('{{ route('agendar.check') }}?' + params.toString());
      if (!res.ok) throw new Error('Error checking availability');
      
      const json = await res.json();
      return json.available === true;
    } catch (e) {
      console.error('Error:', e);
      return false;
    }
  }

  /**
   * Actualizar estado del botón
   */
  async function updateButtonState() {
    // Verificar que todos los campos requeridos estén completos
    const allFieldsFilled = categoria.value && servicioSel.value && 
                           profesionalSel.value && fecha.value && hora.value;

    if (!allFieldsFilled) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i><span>Completa los campos</span>';
      submitBtn.classList.remove('btn-danger');
      submitBtn.classList.add('btn-primary');
      removeAvailabilityAlert();
      return;
    }

    // Mostrar estado de verificación
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i><span>Verificando disponibilidad...</span>';

    try {
      const isAvailable = await checkAvailability();

      if (isAvailable) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i><span>Reservar Cita</span>';
        submitBtn.classList.remove('btn-danger');
        submitBtn.classList.add('btn-primary');
        removeAvailabilityAlert();
      } else {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-x-circle me-2"></i><span>No disponible</span>';
        submitBtn.classList.remove('btn-primary');
        submitBtn.classList.add('btn-danger');
        showAvailabilityAlert();
      }
    } catch (e) {
      console.error('Error checking availability:', e);
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="bi bi-exclamation-circle me-2"></i><span>Error verificando</span>';
      submitBtn.classList.remove('btn-primary');
      submitBtn.classList.add('btn-danger');
    }
  }

  /**
   * Mostrar alerta de disponibilidad
   */
  function showAvailabilityAlert() {
    removeAvailabilityAlert();
    const alert = document.createElement('div');
    alert.className = 'alert alert-warning alert-dismissible fade show availability-alert';
    alert.innerHTML = `
      <i class="bi bi-exclamation-triangle me-2"></i>
      <strong>Horario no disponible</strong> para el profesional seleccionado. 
      Por favor elige otro horario o profesional.
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    form.insertBefore(alert, form.querySelector('.card'));
  }

  /**
   * Remover alerta de disponibilidad
   */
  function removeAvailabilityAlert() {
    const existingAlert = document.querySelector('.availability-alert');
    if (existingAlert) existingAlert.remove();
  }

  /**
   * Escuchar cambios en campos
   */
  const fieldsToWatch = [profesionalSel, fecha, hora, servicioSel];
  fieldsToWatch.forEach(el => {
    el && el.addEventListener('change', updateButtonState);
  });

  // Estado inicial
  submitBtn.disabled = true;
  submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i><span>Completa los campos</span>';
});
</script>
@endpush