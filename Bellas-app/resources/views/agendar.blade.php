@extends('layouts.booking')

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
                      <option value="{{ $cat->id }}" @selected(old('categoria') == $cat->id)>{{ $cat->nombre }}</option>
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
                  <select id="hora" name="hora" class="form-select form-select-lg @error('hora') is-invalid @enderror" required disabled>
                    <option value="">-- Selecciona fecha primero --</option>
                  </select>
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
  const categoria = document.getElementById('categoria');
  const servicioSel = document.getElementById('servicio_id');
  const profesionalSel = document.getElementById('profesional_id');
  const fecha = document.getElementById('fecha');
  const hora = document.getElementById('hora');
  const submitBtn = document.getElementById('submitBtn');
  const form = document.querySelector('form');

  // Set minimum date to today
  const today = new Date().toISOString().split('T')[0];
  fecha.setAttribute('min', today);

  // Configuración de horarios
  const INTERVAL = 15;   // 15 minutos

  /**
   * Generar opciones de hora con intervalos de 15 minutos dentro de un rango
   */
  function generateTimeOptions(startHour, endHour) {
    const options = [];
    for (let h = startHour; h < endHour; h++) {
      for (let m = 0; m < 60; m += INTERVAL) {
        const hour = String(h).padStart(2, '0');
        const min = String(m).padStart(2, '0');
        const time = `${hour}:${min}`;
        const timeLabel = h > 12 ? `${h - 12}:${min} PM` : h === 12 ? `12:${min} PM` : `${h}:${min} AM`;
        options.push({ value: time, label: timeLabel });
      }
    }
    return options;
  }

  // Almacenar opciones de tiempo para cada horario del profesional
  let timeOptions = [];

  /**
   * Cargar servicios por categoría
   */
  async function loadServices(cat) {
    if (!cat) {
      servicioSel.innerHTML = '<option value="">-- Selecciona categoría primero --</option>';
      servicioSel.disabled = true;
      return;
    }

    servicioSel.innerHTML = '<option value="">⏳ Cargando servicios...</option>';
    servicioSel.disabled = true;
    
    try {
      const url = '{{ route('agendar.services') }}?categoria=' + encodeURIComponent(cat);
      console.log('Cargando servicios desde:', url);
      console.log('Categoría ID:', cat);
      
      const res = await fetch(url, {
        method: 'GET',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      
      console.log('Response status:', res.status);
      
      if (!res.ok) {
        const errorText = await res.text();
        console.error('Error HTTP:', res.status, res.statusText);
        console.error('Response body:', errorText);
        throw new Error(`Error al cargar servicios (HTTP ${res.status})`);
      }
      
      const data = await res.json();
      console.log('Servicios cargados:', data);
      console.log('Número de servicios:', Array.isArray(data) ? data.length : 0);
      
      if (!Array.isArray(data) || data.length === 0) {
        servicioSel.innerHTML = '<option value="">No hay servicios disponibles para esta categoría</option>';
        servicioSel.disabled = true;
        console.warn('No se encontraron servicios');
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
      console.log('Servicios cargados exitosamente');
    } catch (e) {
      console.error('Error al cargar servicios:', e);
      servicioSel.innerHTML = '<option value="">Error al cargar servicios</option>';
      servicioSel.disabled = true;
    }
  }

  /**
   * Cargar horas disponibles para una fecha
   */
  async function loadAvailableTimes(selectedDate) {
    if (!selectedDate) {
      hora.innerHTML = '<option value="">-- Selecciona fecha primero --</option>';
      hora.disabled = true;
      return;
    }

    if (!profesionalSel.value || !servicioSel.value) {
      hora.innerHTML = '<option value="">-- Selecciona profesional y servicio primero --</option>';
      hora.disabled = true;
      return;
    }

    hora.innerHTML = '<option value="">⏳ Cargando horarios disponibles...</option>';
    hora.disabled = true;

    try {
      // Primero obtener los horarios de trabajo del profesional para este día
      const scheduleRes = await fetch('{{ route('agendar.schedule') }}?' + new URLSearchParams({
        profesional_id: profesionalSel.value,
        fecha: selectedDate
      }).toString());

      if (!scheduleRes.ok) {
        throw new Error('Error al cargar horarios del profesional');
      }

      const scheduleData = await scheduleRes.json();
      const horarios = scheduleData.horarios || [];

      if (horarios.length === 0) {
        hora.innerHTML = '<option value="">El profesional no tiene horario definido para este día</option>';
        hora.disabled = true;
        console.warn('Sin horarios definidos para el profesional');
        return;
      }

      // Generar opciones de tiempo basadas en los horarios disponibles
      const availableTimes = [];
      
      // Para cada bloque de horario disponible
      for (const horario of horarios) {
        const [startHour, startMin] = horario.hora_inicio.split(':').map(Number);
        const [endHour, endMin] = horario.hora_fin.split(':').map(Number);
        
        // Generar opciones dentro de este bloque
        const blockOptions = generateTimeOptions(startHour, endHour + 1);
        
        // Filtrar para que no salga del horario final
        for (const timeOpt of blockOptions) {
          const [timeHour, timeMin] = timeOpt.value.split(':').map(Number);
          
          // No permitir horas después del hora_fin
          if (timeHour > endHour || (timeHour === endHour && timeMin >= endMin)) {
            continue;
          }
          
          // Verificar si ya existe en availableTimes
          if (!availableTimes.find(t => t.value === timeOpt.value)) {
            availableTimes.push(timeOpt);
          }
        }
      }

      // Ahora verificar disponibilidad para cada hora (conflictos con citas existentes)
      const validTimes = [];

      for (const timeOpt of availableTimes) {
        const res = await fetch('{{ route('agendar.check') }}?' + new URLSearchParams({
          profesional_id: profesionalSel.value,
          fecha: selectedDate,
          hora: timeOpt.value,
          servicio_id: servicioSel.value
        }).toString());

        if (res.ok) {
          const json = await res.json();
          if (json.available) {
            validTimes.push(timeOpt);
          }
        }
      }

      if (validTimes.length === 0) {
        hora.innerHTML = '<option value="">No hay horarios disponibles para esta fecha</option>';
        hora.disabled = true;
        console.warn('No hay horarios disponibles');
        return;
      }

      hora.innerHTML = '<option value="">-- Selecciona un horario --</option>';
      validTimes.forEach(timeOpt => {
        const opt = document.createElement('option');
        opt.value = timeOpt.value;
        opt.textContent = timeOpt.label;
        hora.appendChild(opt);
      });

      hora.disabled = false;
      console.log('Horarios disponibles cargados:', validTimes.length);
    } catch (e) {
      console.error('Error al cargar horarios disponibles:', e);
      hora.innerHTML = '<option value="">Error al cargar horarios disponibles</option>';
      hora.disabled = true;
    }
  }

  /**
   * Cambio de categoría
   */
  categoria && categoria.addEventListener('change', function() {
    console.log('=== CAMBIO DE CATEGORÍA ===');
    console.log('Valor de categoría:', this.value);
    console.log('Tipo:', typeof this.value);
    
    if (this.value) {
      console.log('Llamando loadServices con ID:', this.value);
      loadServices(this.value);
      servicioSel.value = '';
      hora.value = '';
      submitBtn.disabled = true;
      updateButtonState();
    } else {
      console.log('Categoría vacía, limpiando servicios');
      servicioSel.innerHTML = '<option value="">-- Selecciona categoría primero --</option>';
      servicioSel.disabled = true;
      hora.innerHTML = '<option value="">-- Selecciona fecha primero --</option>';
      hora.disabled = true;
      submitBtn.disabled = true;
      updateButtonState();
    }
  });

  /**
   * Cambio de fecha
   */
  fecha && fecha.addEventListener('change', function() {
    if (this.value) {
      loadAvailableTimes(this.value);
      hora.value = '';
      submitBtn.disabled = true;
      updateButtonState();
    } else {
      hora.innerHTML = '<option value="">-- Selecciona fecha primero --</option>';
      hora.disabled = true;
      submitBtn.disabled = true;
      updateButtonState();
    }
  });

  /**
   * Cambio de servicio o profesional
   */
  [servicioSel, profesionalSel].forEach(el => {
    el && el.addEventListener('change', function() {
      if (fecha.value) {
        loadAvailableTimes(fecha.value);
        hora.value = '';
      }
      submitBtn.disabled = true;
      updateButtonState();
    });
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
      const url = '{{ route('agendar.check') }}?' + params.toString();
      console.log('Verificando disponibilidad:', url);
      
      const res = await fetch(url);
      console.log('Response status:', res.status);
      
      if (!res.ok) {
        const errorText = await res.text();
        console.error('Error checking availability:', res.status, errorText);
        throw new Error('Error checking availability (HTTP ' + res.status + ')');
      }
      
      const json = await res.json();
      console.log('Availability response:', json);
      return json.available === true;
    } catch (e) {
      console.error('Error en checkAvailability:', e);
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
  const fieldsToWatch = [profesionalSel, hora, servicioSel];
  fieldsToWatch.forEach(el => {
    el && el.addEventListener('change', updateButtonState);
  });

  // Estado inicial
  submitBtn.disabled = true;
  submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i><span>Completa los campos</span>';
});
</script>
@endpush