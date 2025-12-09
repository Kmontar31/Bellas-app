# 📅 Sistema de Disponibilidad de Profesionales

## Descripción General

El sistema ha sido actualizado para incluir un completo **sistema de disponibilidad de profesionales** que controla cuándo los clientes pueden agendar citas. Esta es una característica crítica que previene conflictos y asegura que las citas se agendan solo durante horas de trabajo autorizadas.

## Características Implementadas

### 1. ✅ Gestión de Horarios de Disponibilidad (Admin)

Los administradores pueden definir los horarios de trabajo para cada profesional:

- **Crear Horarios**: Navega a `Admin > Disponibilidad > Nuevo Horario`
- **Editar Horarios**: Modifica horarios existentes desde la lista
- **Eliminar Horarios**: Borra horarios que ya no aplican
- **Ver Calendario**: Visualiza los horarios en un calendario interactivo

#### Estructura de un Horario:
```
- Profesional: Quién tiene disponibilidad
- Día de la Semana: Cuándo (Lunes, Martes, etc.)
- Hora Inicio: Cuándo comienza (ej: 09:00)
- Hora Fin: Cuándo termina (ej: 18:00)
```

### 2. ✅ Validación en Formulario de Reserva Pública

El formulario de reserva público ahora:

1. **Lee automáticamente los horarios del profesional** basado en la fecha seleccionada
2. **Filtra horas disponibles** para que solo muestre opciones dentro del horario definido
3. **Valida conflictos** con citas existentes
4. **Informa al cliente** si el profesional no tiene disponibilidad

#### Flujo de Usuario:
```
1. Cliente elige Categoría
   ↓
2. Cliente elige Servicio (carga dinámicamente)
   ↓
3. Cliente elige Profesional
   ↓
4. Cliente elige Fecha
   ↓
5. Sistema carga horarios del profesional para ese día
   ↓
6. Cliente elige Hora (solo horas disponibles según horarios)
   ↓
7. Sistema valida conflictos y disponibilidad
   ↓
8. Cita se crea solo si todo es válido
```

### 3. ✅ Validación en Backend

El endpoint `/agendar/check-availability` ahora verifica:

1. ✅ **Conflicto con citas existentes**: No hay dos citas al mismo tiempo
2. ✅ **Disponibilidad de horarios**: El profesional tiene un horario definido para ese día
3. ✅ **Rango horario**: La cita cabe completamente dentro del horario disponible

## Archivos Modificados/Creados

### Vistas Nuevas:
```
✅ resources/views/admin/horarios/create.blade.php    - Crear horarios
✅ resources/views/admin/horarios/edit.blade.php      - Editar horarios
✅ resources/views/admin/horarios/show.blade.php      - Ver detalles
```

### Vistas Mejoradas:
```
✅ resources/views/admin/horarios/index.blade.php     - Interfaz mejorada con más info
✅ resources/views/agendar.blade.php                   - Lógica de horarios dinámicos
```

### Controladores Modificados:
```
✅ app/Http/Controllers/AgendaController.php
   - checkAvailability() - Ahora verifica disponibilidad de horarios
   - getProfessionalSchedule() - NUEVO método para obtener horarios
```

### Rutas Nuevas:
```
✅ GET /agendar/professional-schedule - Obtiene horarios de un profesional para un día
```

## Cómo Usar

### Para Administradores:

#### 1. Crear horarios de disponibilidad:
```
1. Ir a Admin > Disponibilidad
2. Clic en "Nuevo Horario"
3. Seleccionar:
   - Profesional (ej: María)
   - Día de Semana (ej: Lunes)
   - Hora Inicio (ej: 09:00)
   - Hora Fin (ej: 18:00)
4. Guardar
```

**Ejemplo:**
- Profesional: María
- Día: Lunes a Viernes (crear 5 horarios, uno por día)
- Horario: 09:00 - 13:00 (mañana) + 14:00 - 18:00 (tarde)

Esto crearía bloques separados de disponibilidad.

#### 2. Ver horarios en calendario:
```
Admin > Disponibilidad > Ver Calendario
```

#### 3. Gestionar horarios:
- **Editar**: Clic en "Editar" en la tabla
- **Eliminar**: Clic en "Eliminar" (se pide confirmación)

### Para Clientes:

#### 1. Agendar cita con validación de disponibilidad:
```
1. Ir a "Reservar Cita"
2. Seleccionar categoría
3. Seleccionar servicio
4. Seleccionar profesional
5. Seleccionar fecha
   → El sistema carga los horarios disponibles del profesional
6. Seleccionar hora
   → Solo aparecen horas dentro del horario definido
7. Enviar formulario
   → El sistema valida nuevamente en backend
```

**Casos de Error:**
- "El profesional no tiene horario definido para este día" → Elige otro día
- "No hay horarios disponibles para esta fecha" → Todas las horas están ocupadas
- "El horario solicitado no está disponible" → Elige una hora dentro del rango

## Lógica de Validación

### Backend (checkAvailability):
```php
1. Verificar que profesional existe
2. Verificar que NO hay cita conflictiva en esa hora
3. Verificar que el profesional TIENE horarios definidos para ese día
4. Verificar que la hora solicitada CABE dentro del horario disponible
   - inicio >= hora_inicio del horario
   - fin <= hora_fin del horario
5. Si pasa todas las validaciones → Disponible ✅
```

### Frontend (agendar.blade.php):
```javascript
1. Obtener horarios del profesional para la fecha
2. Generar opciones de tiempo basadas en esos horarios
3. Para cada hora: verificar conflicto con citas existentes
4. Mostrar solo horas sin conflictos
```

## Ejemplos de Escenarios

### Escenario 1: Profesional sin horarios definidos
```
Cliente intenta agendar con "María" en "Lunes 10:00"
↓
Sistema consulta horarios de María para lunes
↓
No hay horarios definidos
↓
"El profesional no tiene horario definido para este día"
↓
Cliente elige otro día o profesional
```

### Escenario 2: Hora fuera del rango
```
Admin definió: María trabaja Lunes 09:00-14:00
Cliente intenta: Agendarse a las 15:00

1. Frontend obtiene horarios: [09:00-14:00]
2. Genera opciones: 09:00, 09:15, 09:30, ..., 13:45
3. 15:00 NO aparece en la lista
4. Cliente no puede seleccionarla
```

### Escenario 3: Disponibilidad con servicio de duración
```
Admin definió: Carlos trabaja Martes 09:00-17:00
Cliente quiere: Servicio de 2 horas, a las 16:00

1. Sistema calcula: 16:00 + 2 horas = 18:00
2. 18:00 > 17:00 (hora fin)
3. No encaja en el horario
4. No se muestra la opción de 16:00
5. Última opción disponible: 15:00 (15:00 + 2h = 17:00 ✅)
```

## Validación Cruzada

El sistema ahora realiza validación en **dos niveles**:

### Nivel 1: Cliente (JavaScript en agendar.blade.php)
- Rápido: Lee desde el servidor y filtra localmente
- Mejora UX: Solo muestra opciones válidas
- No es seguro: El usuario podría saltárselo

### Nivel 2: Servidor (checkAvailability en AgendaController)
- Seguro: Valida nuevamente en backend
- Previene tampering: Un cliente no puede saltarse validaciones
- Retorna mensajes de error específicos

Esto asegura que **incluso si alguien intenta enviar datos inválidos directamente**, el servidor los rechazará.

## Notas Técnicas

### Almacenamiento de Horarios
```sql
CREATE TABLE horarios (
    id PRIMARY KEY,
    profesional_id FOREIGN KEY,
    dia_semana INT (0=Domingo, 1=Lunes, ..., 6=Sábado),
    hora_inicio TIME (formato HH:MM:SS),
    hora_fin TIME (formato HH:MM:SS)
)
```

### Comparación de Horas
- Las horas se almacenan como **TIME** (HH:MM:SS)
- Las comparaciones usan **string comparison** (lexicographically)
- "09:00:00" < "17:00:00" funciona correctamente

### Día de la Semana (dayOfWeek)
- Carbon usa: 0=Sunday, 1=Monday, ..., 6=Saturday
- Database usa: 0=Sunday, 1=Monday, ..., 6=Saturday
- ✅ Son compatibles, sin necesidad de conversión

## Próximas Mejoras Posibles

1. **Bloqueos especiales**: Feriados, días de descanso
2. **Múltiples bloques por día**: Mañana (09:00-13:00) + Tarde (14:00-18:00)
3. **Notificaciones**: Avisos cuando no hay disponibilidad
4. **Disponibilidad de múltiples profesionales**: "Agendar con cualquiera disponible"
5. **Sincronización con Google Calendar**: Integración con calendario externo

## Troubleshooting

### "No hay horarios disponibles para esta fecha"
→ Verifica que el profesional tiene horarios definidos
→ Crea horarios en Admin > Disponibilidad

### "El profesional no tiene horario definido para este día"
→ El día de semana no tiene horarios
→ Asegúrate de crear horarios para TODOS los días que atiende

### Las horas no cambian al seleccionar profesional
→ Recarga la página (Ctrl+F5)
→ Verifica que JavaScript no tiene errores en consola (F12)

---

**Última actualización**: Diciembre 2024
