# 🎬 Guía de Prueba Rápida - Sistema de Disponibilidad

**Tiempo estimado**: 5-10 minutos

---

## ✅ Checklist Pre-Prueba

- [ ] Laravel está corriendo
- [ ] Base de datos está actualizada
- [ ] Navegador sin caché (F12 → Network → Disable cache)

---

## 📍 Paso 1: Acceder al Panel Admin

1. Abrir: `http://localhost/Bellas-app/admin/horarios`
2. Loguear con credenciales de admin si es necesario
3. Deberías ver: Tabla vacía o con horarios existentes

**Esperado**: Lista de disponibilidad

---

## 🔧 Paso 2: Crear un Horario de Disponibilidad

### 2A: Ir a Crear Nuevo Horario
```
1. Clic en botón azul "Nuevo Horario"
2. URL debe cambiar a: /admin/horarios/create
```

### 2B: Llenar el Formulario
```
Profesional: [Seleccionar el primero disponible, ej: María]
Día de la Semana: [Seleccionar LUNES]
Hora Inicio: [Escribir 09:00]
Hora Fin: [Escribir 18:00]
```

### 2C: Guardar
```
1. Clic en botón "Guardar Horario"
2. Página debe redirigir a /admin/horarios
```

**Esperado**: 
- ✅ Nuevo horario en la tabla
- ✅ Profesional, Día (Lunes), Horas (09:00-18:00)
- ✅ Duración mostrada (9h)

---

## 📋 Paso 3: Crear Más Horarios (Opcional)

Repite Paso 2 para otros días:
```
Lunes: 09:00-18:00
Martes: 09:00-18:00
Miércoles: 09:00-18:00
...
```

O al menos un día (Lunes es suficiente para testear)

---

## 🌐 Paso 4: Ir a Formulario Público de Reservas

1. Abrir nueva pestaña
2. Ir a: `http://localhost/Bellas-app/agendar`
3. Deberías ver: Formulario "Reserva tu Cita"

**Esperado**: Página pública de reservas con campos

---

## 📝 Paso 5: Llenar el Formulario de Reservas

### 5A: Información Personal
```
Nombre: [Ej: Juan]
Email: [Ej: juan@example.com]
Teléfono: [Ej: 1234567890]
```

### 5B: Seleccionar Servicio
```
Categoría: [Seleccionar cualquiera]
→ El dropdown de Servicios debe llenarse
Servicio: [Seleccionar uno]
Profesional: [Seleccionar el mismo que creaste horario]
```

**Crítico**: Debe ser el profesional con horario creado (ej: María)

### 5C: Seleccionar Fecha
```
Fecha: [Seleccionar el LUNES de próxima semana]
→ Debe cargar horas disponibles
→ Mostrará: ⏳ Cargando horarios disponibles...
→ Luego mostrará horas dentro de 09:00-18:00
```

**Esperado**: Opciones de hora aparecen (09:00, 09:15, 09:30, ...)

### 5D: Seleccionar Hora
```
Hora: [Seleccionar cualquiera, ej: 10:00]
```

**Esperado**: Solo muestra horas dentro del rango 09:00-18:00

### 5E: Enviar Formulario
```
Clic en "Agendar Cita"
Página debe mostrar: ✅ "¡Éxito! Tu reserva ha sido registrada"
```

---

## ✅ Paso 6: Verificar Cita Creada

### 6A: En Admin
```
1. Ir a: Admin > Citas (o Calendario)
2. Buscar la cita creada
3. Debe mostrar:
   - Cliente: Juan
   - Profesional: [El que seleccionaste]
   - Fecha: [Lunes seleccionado]
   - Hora: [10:00]
```

### 6B: En Horarios
```
1. Ir a: Admin > Disponibilidad
2. Ver tabla de horarios
3. Debe estar el horario de Lunes 09:00-18:00
```

---

## 🧪 Paso 7: Pruebas de Validación (Avanzado)

### Test A: Día Sin Horario
```
1. Volver a /agendar
2. Seleccionar DOMINGO (día sin horario)
3. Resultado esperado:
   ✅ "El profesional no tiene horario definido para este día"
```

### Test B: Hora Fuera de Rango
```
1. Seleccionar LUNES (con horario 09:00-18:00)
2. Esperar a que carguen horas
3. Intentar ver si aparece 08:00 o 19:00
4. Resultado esperado:
   ✅ NO aparecen, solo 09:00-17:45
```

### Test C: Servicio con Duración
```
1. Seleccionar servicio de 2 horas (120 min)
2. Seleccionar LUNES a las 17:00
3. Resultado esperado:
   ✅ NO aparece (17:00 + 2h = 19:00 > 18:00)
   ✅ Máximo disponible será 16:45 (16:45 + 2h = 18:45... wait)
   
   En realidad:
   ✅ Si el horario es 09:00-18:00
   ✅ Y servicio es 2h (120 min)
   ✅ Última opción disponible: 16:00 (16:00 + 2h = 18:00 ✓)
```

---

## 📊 Paso 8: Verificar en Calendario Admin

1. Ir a: `Admin > Citas > Ver Calendario` (o similar)
2. Buscar la cita creada
3. Debe mostrar:
   - Fecha correcta
   - Hora correcta
   - Nombre del servicio

**Esperado**: Cita visible en calendario con horario correcto

---

## 🔍 Verificaciones de Debugging

Si algo no funciona, verifica:

### Verificar Horarios en BD
```
1. Ir a phpmyadmin: localhost/phpmyadmin
2. Base de datos: bellas_app
3. Tabla: horarios
4. Buscar registros con profesional_id del test
5. Verificar: dia_semana = 1 (lunes), hora_inicio/fin
```

### Verificar en Consola Browser (F12)
```
1. Abrir /agendar
2. F12 → Consola
3. Seleccionar profesional y fecha
4. Debe ver logs:
   - "Cargando horarios disponibles..."
   - "Horarios disponibles cargados: X"
   
Si hay error:
   - "Error al cargar horarios disponibles: ..."
   - Verificar endpoint GET /agendar/professional-schedule
```

### Verificar Network (F12)
```
1. F12 → Network
2. Seleccionar fecha
3. Debe ver peticiones:
   - GET /agendar/professional-schedule?... (status 200)
   - GET /agendar/check-availability?... (status 200)

Si hay 404:
   - Ruta no está registrada
   - Revisar routes/web.php
```

---

## 🐛 Errores Comunes y Soluciones

### Error 1: "Sin horarios disponibles"
```
Causa: No creaste horario para ese día
Solución: Crea horario en Admin > Disponibilidad
```

### Error 2: "El profesional no tiene horario definido"
```
Causa: Seleccionaste día sin horario (ej: domingo)
Solución: Crea horario para ese día O selecciona otro día
```

### Error 3: Las horas no cambian al cambiar fecha
```
Causa: JavaScript no ejecutó loadAvailableTimes()
Solución:
  1. Recarga página (Ctrl+F5)
  2. Abre consola (F12)
  3. Busca errores rojos
  4. Si hay errores, reportar
```

### Error 4: Aparecen horas fuera del rango
```
Causa: Sistema no cargó horarios correctamente
Solución:
  1. Verificar en phpmyadmin que horarios existen
  2. Verificar endpoint GET /agendar/professional-schedule retorna JSON correcto
  3. Recargar página
```

### Error 5: "No hay horarios disponibles para esta fecha"
```
Causa: Profesional tiene horario pero TODAS las horas están ocupadas
Solución:
  1. Agregar más horarios (horario matutino + vespertino)
  2. O Eliminar citas existentes para liberar horas
  3. O Seleccionar otra fecha
```

---

## ✨ Checklist Final de Prueba

- [ ] ✅ Creé horario en admin
- [ ] ✅ Horario aparece en tabla
- [ ] ✅ Llené formulario público
- [ ] ✅ Las horas filtraron según profesional/fecha
- [ ] ✅ Formulario aceptó la reserva
- [ ] ✅ Cita aparece en admin
- [ ] ✅ Cita muestra hora correcta
- [ ] ✅ Intenté día sin horario → mostró error
- [ ] ✅ Intenté hora fuera de rango → no aparecía
- [ ] ✅ No hay errores en consola (F12)

---

## 🎉 ¡Éxito!

Si todo pasó las pruebas:
- ✅ Sistema de disponibilidad está **completamente funcional**
- ✅ Clientes pueden agendar **solo en horarios válidos**
- ✅ Admin tiene **control total** de disponibilidad
- ✅ Base de datos **protegida** con validación doble

---

## 📞 Próximos Pasos

1. **Crear más horarios** para otros profesionales
2. **Testear** con diferentes servicios de duración variable
3. **Implementar** bloqueos de feriados (si es necesario)
4. **Agregar** notificaciones de confirmación (opcional)
5. **Monitorear** en producción

---

**Duración estimada**: 5-10 minutos  
**Nivel de dificultad**: Fácil  
**Riesgo**: Ninguno (no modifica datos permanentemente si solo testeas)

¡Disfruta probando el sistema! 🚀

