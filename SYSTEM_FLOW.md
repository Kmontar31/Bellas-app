# 🔄 Diagrama del Flujo de Disponibilidad

## Arquitectura del Sistema

```
┌─────────────────────────────────────────────────────────────────┐
│                      BASE DE DATOS                              │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │  HORARIOS    │  │   AGENDA     │  │  BLOQUEOS    │          │
│  │              │  │              │  │              │          │
│  │- prof_id    │  │- cliente_id  │  │- prof_id     │          │
│  │- dia_semana │  │- prof_id     │  │- fecha       │          │
│  │- hora_inicio│  │- fecha       │  │- tipo        │          │
│  │- hora_fin   │  │- hora_inicio │  │              │          │
│  │              │  │- hora_fin    │  │              │          │
│  └──────────────┘  └──────────────┘  └──────────────┘          │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

## Flujo de Creación de Horarios (Admin)

```
ADMIN
  │
  ├─→ Admin > Disponibilidad
  │    └─→ Clic "Nuevo Horario"
  │         └─→ HorariosController@create
  │              └─→ resources/views/admin/horarios/create.blade.php
  │                   └─→ FORM POST /admin/horarios
  │
  └─→ HorariosController@store
       ├─→ Validación (profesional, día, hora_inicio, hora_fin)
       ├─→ Horario::create($request->all())
       │    └─→ INSERT INTO horarios (...)
       │
       └─→ Redirige a Admin > Disponibilidad (index)
```

## Flujo de Validación en Agendar (Cliente)

```
CLIENTE
  │
  └─→ /agendar (Página pública)
       │
       ├─→ 1️⃣ Selecciona Categoría
       │    └─→ JavaScript carga servicios
       │         └─→ GET /agendar/services?categoria={id}
       │              └─→ AgendaController@servicesByCategory
       │                   └─→ JSON: [servicios]
       │
       ├─→ 2️⃣ Selecciona Servicio (cargó dinámicamente)
       │
       ├─→ 3️⃣ Selecciona Profesional
       │
       ├─→ 4️⃣ Selecciona Fecha
       │    └─→ JavaScript carga horarios
       │         └─→ GET /agendar/professional-schedule
       │              ?profesional_id={id}&fecha={fecha}
       │              │
       │              └─→ AgendaController@getProfessionalSchedule
       │                   ├─→ Parse fecha → día de semana
       │                   ├─→ SELECT FROM horarios
       │                   │    WHERE profesional_id = {id}
       │                   │    AND dia_semana = {dayOfWeek}
       │                   └─→ JSON: {horarios: [...]}
       │
       │    JavaScript genera opciones de tiempo
       │    basadas en horarios recibidos
       │
       ├─→ 5️⃣ Selecciona Hora
       │    └─→ JavaScript valida cada hora
       │         └─→ GET /agendar/check-availability
       │              ?profesional_id={id}
       │              &fecha={fecha}
       │              &hora={hora}
       │              &servicio_id={servicio_id}
       │              │
       │              └─→ AgendaController@checkAvailability
       │                   ├─→ Verificar conflicto con citas
       │                   ├─→ Verificar horarios disponibles
       │                   ├─→ Verificar rango horario
       │                   └─→ JSON: {available: true|false}
       │
       ├─→ 6️⃣ Valida internamente
       │    └─→ Solo muestra horas que pasan validación
       │
       └─→ 7️⃣ Envía formulario
            └─→ POST /agendar
                 ├─→ AgendaController@publicStore
                 │    ├─→ Valida datos
                 │    ├─→ checkAvailability() una vez más
                 │    ├─→ Agenda::create($data)
                 │    │    └─→ INSERT INTO agenda (...)
                 │    │
                 │    └─→ Redirige a página de éxito
                 │
                 └─→ ✅ Cita creada
```

## Validación en checkAvailability

```
POST /agendar/check-availability
    │
    ├─→ 1️⃣ Validar parámetros
    │    ├─ profesional_id ✓
    │    ├─ fecha ✓
    │    ├─ hora ✓
    │    └─ servicio_id (opcional)
    │
    ├─→ 2️⃣ Calcular rango de cita
    │    ├─ start = fecha + hora_inicio
    │    └─ end = start + duracion_servicio
    │
    ├─→ 3️⃣ Verificar CONFLICTOS
    │    └─ SELECT FROM agenda
    │        WHERE profesional_id = {id}
    │        AND fecha = {fecha}
    │        AND (hora_inicio < {end} AND hora_fin > {start})
    │
    │    └─ Si existe: ❌ NOT available
    │
    ├─→ 4️⃣ Verificar DISPONIBILIDAD DE HORARIOS
    │    ├─ Parse fecha → día de semana
    │    └─ SELECT FROM horarios
    │         WHERE profesional_id = {id}
    │         AND dia_semana = {dayOfWeek}
    │
    │    └─ Si vacío: ❌ NOT available
    │
    └─→ 5️⃣ Verificar RANGO HORARIO
         ├─ Para cada horario disponible:
         │  ├─ Si (hora_inicio >= horario.hora_inicio
         │  │    AND hora_fin <= horario.hora_fin)
         │  └─ Entonces: ✅ available = true
         │
         └─ Si ninguno cumple: ❌ NOT available

RESPUESTA JSON: {available: true|false, reason?: "..."}
```

## Estados y Transiciones

```
HORARIO (Disponibilidad)
┌────────────────────┐
│ Profesional + Día  │ → Validaciones
│ + Hora inicio/fin  │
└────────────────────┘
         │
         ├─→ El profesional PUEDE trabajar en este horario
         │
         └─→ Se usa para FILTRAR horas en /agendar

AGENDA (Cita)
┌──────────────────────┐
│ Cliente + Profesional│ → Estados
│ + Servicio + Fecha   │
│ + Hora inicio/fin    │
└──────────────────────┘
         │
         ├─→ pendiente (inicial)
         ├─→ confirmada (admin confirma)
         ├─→ completada (cita termina)
         └─→ cancelada (cancelado)

VALIDACIÓN DE CITA
┌─────────────────────┐
│ Nueva cita solicitada│
└─────────────────────┘
         │
         ├─→ ¿Profesional tiene horario para este día?
         │    └─ NO → Rechazar
         │
         ├─→ ¿Cita entra en horario disponible?
         │    └─ NO → Rechazar
         │
         ├─→ ¿Hay otra cita en ese horario?
         │    └─ SÍ → Rechazar
         │
         └─→ ✅ Crear cita
```

## Ejemplo Práctico

### Configuración Inicial
```
Profesional: MARÍA
Horarios:
  - Lunes 09:00-13:00
  - Lunes 14:00-18:00
  - Martes 09:00-18:00
  - ... (etc)

Citas existentes:
  - Lunes 09:30-10:30 (Servicio A)
  - Martes 15:00-16:00 (Servicio B)
```

### Cliente intenta agendar
```
✅ CASO 1: Lunes 10:45, Servicio A (60 min)
   - Hora solicitada: 10:45-11:45
   - ¿Tiene horario Lunes? → SÍ (09:00-13:00)
   - ¿Entra en horario? → SÍ (10:45-11:45 dentro de 09:00-13:00)
   - ¿Conflicto cita? → NO (09:30-10:30 < 10:45)
   → ✅ DISPONIBLE

❌ CASO 2: Lunes 09:30, Servicio A (60 min)
   - Hora solicitada: 09:30-10:30
   - ¿Tiene horario Lunes? → SÍ
   - ¿Entra en horario? → SÍ
   - ¿Conflicto cita? → SÍ (existe cita 09:30-10:30)
   → ❌ NO DISPONIBLE

❌ CASO 3: Domingo 10:00
   - Hora solicitada: 10:00-11:00
   - ¿Tiene horario Domingo? → NO (no hay entrada en tabla)
   → ❌ NO DISPONIBLE

❌ CASO 4: Lunes 13:30, Servicio A (60 min)
   - Hora solicitada: 13:30-14:30
   - ¿Tiene horario Lunes? → SÍ (09:00-13:00 y 14:00-18:00)
   - ¿Entra en horario? → NO (13:30-14:30 no cabe en ningún bloque)
   → ❌ NO DISPONIBLE
```

## Flujo de Datos

### JSON: getProfessionalSchedule Response
```json
{
  "horarios": [
    {
      "hora_inicio": "09:00:00",
      "hora_fin": "13:00:00"
    },
    {
      "hora_inicio": "14:00:00",
      "hora_fin": "18:00:00"
    }
  ],
  "dayOfWeek": 1,
  "fecha": "2024-12-10"
}
```

### JSON: checkAvailability Response
```json
DISPONIBLE:
{
  "available": true
}

NO DISPONIBLE (con razón):
{
  "available": false,
  "reason": "Ya existe una cita en este horario"
}

{
  "available": false,
  "reason": "El profesional no tiene horario definido para este día"
}

{
  "available": false,
  "reason": "El horario solicitado no está disponible"
}
```

## Casos de Error y Recuperación

```
ERROR: "El profesional no tiene horario definido"
├─ Causa: No hay registros en horarios para día de semana
├─ Solución (Admin): Crear horarios en Admin > Disponibilidad
└─ Cliente: Elige otro profesional o fecha

ERROR: "No hay horarios disponibles para esta fecha"
├─ Causa: Todas las horas de trabajo están ocupadas
├─ Solución (Admin): Criar más horarios (agregar bloques)
└─ Cliente: Elige otra fecha

ERROR: Hora no disponible en dropdown
├─ Causa: Fuera del rango de horarios del profesional
├─ Solución (Admin): Ampliar horarios
└─ Cliente: Elige hora dentro del rango mostrado
```

---

**Diagrama actualizado**: Diciembre 2024
