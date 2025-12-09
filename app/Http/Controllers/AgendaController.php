<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agenda;
use App\Models\Cliente;
use App\Models\Profesional;
use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\Bloqueo;
use App\Models\Horario;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profesionales = Profesional::all();
        $servicios = Servicio::all();
        $citas = Agenda::with(['cliente', 'profesional', 'servicio'])
            ->orderBy('fecha', 'asc')
            ->orderBy('hora_inicio', 'asc')
            ->get();

        return view('admin.agenda.index', compact('citas', 'profesionales', 'servicios'));
    }

    /**
     * Store a public appointment (from agendar page)
     */
    public function publicStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'nullable|string|max:50',
            'fecha' => 'required|date',
            'hora' => 'required', // we'll parse time
            'categoria' => 'required|exists:categorias,id',
            'servicio_id' => 'required|exists:servicios,id',
            'profesional_id' => 'required|exists:profesionales,id',
            'message' => 'nullable|string'
        ]);

        \Log::info('publicStore data:', $data);

        // Find or create cliente
        $cliente = Cliente::firstOrCreate(
            ['email' => $data['email']],
            ['nombre' => $data['name'], 'telefono' => $data['phone'] ?? null]
        );

        $servicio = Servicio::findOrFail($data['servicio_id']);

        // Compute start and end datetimes
        $start = Carbon::parse($data['fecha'] . ' ' . $data['hora']);
        $end = (clone $start)->addMinutes($servicio->duracion_minutos ?? 60);

        \Log::info('Parsed times - Start: ' . $start . ' (' . $start->format('H:i:s') . '), End: ' . $end . ' (' . $end->format('H:i:s') . ')');

        // Business rule: no overlapping for the profesional
        $conflict = Agenda::where('profesional_id', $data['profesional_id'])
            ->where('fecha', $start->toDateString())
            ->where(function($q) use ($start, $end) {
                $q->where(function($q2) use ($start, $end) {
                    $q2->where('hora_inicio', '<', $end->format('H:i:s'))
                       ->where('hora_fin', '>', $start->format('H:i:s'));
                });
            })->exists();

        if ($conflict) {
            return back()->withInput()->withErrors(['hora' => 'El profesional no está disponible en el horario seleccionado.']);
        }

        $agenda = Agenda::create([
            'cliente_id' => $cliente->id,
            'servicio_id' => $servicio->id,
            'profesional_id' => $data['profesional_id'],
            'fecha' => $start->toDateString(),
            'hora_inicio' => $start->format('H:i:s'),
            'hora_fin' => $end->format('H:i:s'),
            'estado' => 'pendiente',
            'notas' => $data['message'] ?? null
        ]);

        \Log::info('Agenda created - ID: ' . $agenda->id . ', fecha: ' . $agenda->fecha . ', hora_inicio: ' . $agenda->hora_inicio . ', hora_fin: ' . $agenda->hora_fin);

        return redirect()->route('agendar.form')->with('success', 'Reserva creada correctamente. Te contactaremos para confirmar.');
    }

    /**
     * Return services by category (AJAX)
     */
    public function servicesByCategory(Request $request)
    {
        try {
            $categoriaId = $request->query('categoria');
            
            \Log::info('servicesByCategory called with categoria=' . $categoriaId);
            
            // Validate that categoria is numeric
            if (!$categoriaId || !is_numeric($categoriaId)) {
                \Log::warning('categoria is not numeric or missing: ' . $categoriaId);
                return response()->json([], 400);
            }
            
            // Find the categoria by ID
            $categoria = Categoria::find($categoriaId);
            
            if (!$categoria) {
                \Log::warning('categoria not found with id: ' . $categoriaId);
                return response()->json([], 404);
            }
            
            \Log::info('Found categoria: ' . $categoria->nombre);
            
            // Get services related to this category
            $services = Servicio::where('categoria_id', $categoria->id)
                ->orderBy('nombre')
                ->get(['id', 'nombre', 'duracion_minutos', 'precio']);
            
            \Log::info('Found ' . count($services) . ' services for categoria ' . $categoria->id);
            
            // Ensure we return valid JSON array
            return response()->json($services, 200)
                ->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            \Log::error('servicesByCategory error: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    /**
     * Get professional work schedule for a specific date (AJAX)
     */
    public function getProfessionalSchedule(Request $request)
    {
        try {
            $profesionalId = $request->query('profesional_id');
            $fecha = $request->query('fecha');

            if (!$profesionalId || !$fecha) {
                return response()->json(['horarios' => [], 'dayOfWeek' => null], 400);
            }

            $date = Carbon::parse($fecha);
            $dayOfWeek = $date->dayOfWeek; // 0 = Sunday

            // Get available schedules for this professional on this day of week
            $horarios = Horario::where('profesional_id', $profesionalId)
                ->where('dia_semana', $dayOfWeek)
                ->orderBy('hora_inicio')
                ->get(['hora_inicio', 'hora_fin']);

            return response()->json([
                'horarios' => $horarios,
                'dayOfWeek' => $dayOfWeek,
                'fecha' => $fecha
            ]);
        } catch (\Exception $e) {
            \Log::error('getProfessionalSchedule error: ' . $e->getMessage());
            return response()->json(['horarios' => [], 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Check availability for a profesional at given datetime
     */
    public function checkAvailability(Request $request)
    {
        try {
            $profesionalId = $request->query('profesional_id');
            $fecha = $request->query('fecha');
            $hora = $request->query('hora');
            $servicioId = $request->query('servicio_id');

            \Log::info('checkAvailability: profesional_id=' . $profesionalId . ', fecha=' . $fecha . ', hora=' . $hora . ', servicio_id=' . $servicioId);

            // Validate that required params are present
            if (!$profesionalId || !$fecha || !$hora) {
                \Log::warning('Missing required parameters');
                return response()->json(['available' => false], 400);
            }

            // Validate that profesional exists
            if (!Profesional::find($profesionalId)) {
                \Log::warning('Profesional not found: ' . $profesionalId);
                return response()->json(['available' => false], 404);
            }

            $servicio = $servicioId ? Servicio::find($servicioId) : null;
            $start = Carbon::parse($fecha . ' ' . $hora);
            $end = $servicio ? (clone $start)->addMinutes($servicio->duracion_minutos ?? 60) : (clone $start)->addMinutes(60);

            \Log::info('Checking conflict from ' . $start . ' to ' . $end);

            // Verificar conflicto con citas existentes
            $conflict = Agenda::where('profesional_id', $profesionalId)
                ->where('fecha', $start->toDateString())
                ->where(function($q) use ($start, $end) {
                    $q->where('hora_inicio', '<', $end->format('H:i:s'))
                      ->where('hora_fin', '>', $start->format('H:i:s'));
                })->exists();

            \Log::info('Conflict found: ' . ($conflict ? 'yes' : 'no'));

            if ($conflict) {
                return response()->json(['available' => false, 'reason' => 'Ya existe una cita en este horario']);
            }

            // Verificar disponibilidad de horarios (horarios de trabajo del profesional)
            $dayOfWeek = $start->dayOfWeek;
            // Carbon usa 0=Sunday, pero nosotros usamos 0=Domingo, así que es compatible
            
            $horariosDisponibles = Horario::where('profesional_id', $profesionalId)
                ->where('dia_semana', $dayOfWeek)
                ->get();

            \Log::info('Found ' . count($horariosDisponibles) . ' horarios para el día ' . $dayOfWeek);

            // Si no hay horarios definidos, no está disponible
            if ($horariosDisponibles->isEmpty()) {
                \Log::info('No hay horarios definidos para este día');
                return response()->json(['available' => false, 'reason' => 'El profesional no tiene horario definido para este día']);
            }

            // Verificar que la hora solicitada esté dentro de algún horario disponible
            $horaInicio = $start->format('H:i:s');
            $horaFin = $end->format('H:i:s');

            $estaDisponible = false;
            foreach ($horariosDisponibles as $horario) {
                // Verificar que la cita completa esté dentro del horario disponible
                if ($horaInicio >= $horario->hora_inicio && $horaFin <= $horario->hora_fin) {
                    $estaDisponible = true;
                    break;
                }
            }

            if (!$estaDisponible) {
                \Log::info('Horario solicitado (' . $horaInicio . ' - ' . $horaFin . ') no está disponible');
                return response()->json(['available' => false, 'reason' => 'El horario solicitado no está disponible']);
            }

            \Log::info('Disponibilidad confirmada');
            return response()->json(['available' => true]);
        } catch (\Exception $e) {
            \Log::error('checkAvailability error: ' . $e->getMessage());
            return response()->json(['available' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mostrar calendario para administración
     */
    public function calendar()
    {
        return view('admin.agenda.calendar');
    }

    /**
     * Endpoint JSON para eventos (FullCalendar)
     */
    public function events(Request $request)
    {
        $start = $request->query('start') ? Carbon::parse($request->query('start')) : Carbon::now();
        $end = $request->query('end') ? Carbon::parse($request->query('end')) : Carbon::now()->addMonth();
        $profesionalId = $request->query('profesional_id');
        $servicioId = $request->query('servicio_id');
        
        \Log::info('events() called with start=' . $start . ', end=' . $end . ', prof=' . $profesionalId . ', serv=' . $servicioId);
        
        $events = [];

        // 1. Obtener citas
        $citas = Agenda::with(['cliente', 'profesional', 'servicio'])
            ->whereBetween('fecha', [$start->toDateString(), $end->toDateString()])
            ->when($profesionalId, function($query) use ($profesionalId) {
                return $query->where('profesional_id', $profesionalId);
            })
            ->when($servicioId, function($query) use ($servicioId) {
                return $query->where('servicio_id', $servicioId);
            })->get();

        // Convertir citas a eventos
        foreach ($citas as $cita) {
            try {
                // $cita->fecha es un Carbon date, $cita->hora_inicio y hora_fin son strings HH:MM:SS
                $fechaStr = $cita->fecha->format('Y-m-d');
                
                $citaStart = Carbon::parse($fechaStr . ' ' . $cita->hora_inicio);
                $citaEnd = Carbon::parse($fechaStr . ' ' . $cita->hora_fin);

                // Formatear hora en formato HH:MM
                $horaInicio = substr($cita->hora_inicio, 0, 5);

                $event = [
                    'id' => $cita->id,
                    'title' => $horaInicio . ' - ' . ($cita->servicio ? $cita->servicio->nombre : 'Cita'),
                    'start' => $citaStart->toIso8601String(),
                    'end' => $citaEnd->toIso8601String(),
                    'extendedProps' => [
                        'profesional' => optional($cita->profesional)->nombre,
                        'servicio' => optional($cita->servicio)->nombre,
                        'estado' => $cita->estado ?? null,
                        'tipo' => 'cita',
                        'cliente' => optional($cita->cliente)->nombre
                    ],
                    'backgroundColor' => '#dc3545',
                    'borderColor' => '#c82333',
                    'textColor' => '#fff'
                ];
                
                \Log::info('Event created - ID: ' . $cita->id . ', start: ' . $event['start'] . ', end: ' . $event['end']);
                $events[] = $event;
            } catch (\Exception $e) {
                \Log::error('Error parsing cita ' . $cita->id . ': ' . $e->getMessage() . 
                           ' | fecha: ' . $cita->fecha . ' | hora_inicio: ' . $cita->hora_inicio . ' | hora_fin: ' . $cita->hora_fin);
            }
        }

        // 2. Obtener horarios disponibles
        $horarios = Horario::with('profesional')
            ->when($profesionalId, function($query) use ($profesionalId) {
                return $query->where('profesional_id', $profesionalId);
            })->get();

        // Generar eventos de horarios disponibles
        $currentDate = clone $start;
        while ($currentDate <= $end) {
            foreach ($horarios as $horario) {
                if ($currentDate->dayOfWeek == $horario->dia_semana) {
                    $horarioStart = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $horario->hora_inicio);
                    $horarioEnd = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $horario->hora_fin);
                    $events[] = [
                        'title' => 'Disponible - ' . $horario->profesional->nombre,
                        'start' => $horarioStart->toIso8601String(),
                        'end' => $horarioEnd->toIso8601String(),
                        'backgroundColor' => '#28a745',
                        'borderColor' => '#1e7e34',
                        'textColor' => '#fff',
                        'display' => 'background'
                    ];
                }
            }
            $currentDate->addDay();
        }

        // 3. Obtener bloqueos
        $bloqueos = Bloqueo::whereBetween('fecha', [$start->toDateString(), $end->toDateString()])
            ->when($profesionalId, function($query) use ($profesionalId) {
                return $query->where(function($q) use ($profesionalId) {
                    $q->where('profesional_id', $profesionalId)
                      ->orWhereNull('profesional_id');
                });
            })->get();

        foreach ($bloqueos as $bloqueo) {
            $events[] = [
                'id' => 'b-' . $bloqueo->id,
                'title' => 'Bloqueado' . ($bloqueo->profesional ? ' - ' . $bloqueo->profesional->nombre : ''),
                'start' => $bloqueo->fecha->toDateString(),
                'end' => Carbon::parse($bloqueo->fecha)->addDay()->toDateString(),
                'allDay' => true,
                'extendedProps' => [
                    'block' => true,
                    'tipo' => 'bloqueo',
                    'motivo' => $bloqueo->motivo,
                    'profesional' => optional($bloqueo->profesional)->nombre
                ],
                'className' => 'bloqueado'
            ];
        }

        \Log::info('Returning ' . count($events) . ' events');
        return response()->json($events);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $profesionales = Profesional::all();
        $servicios = Servicio::all();
        $clientes = Cliente::all();
        return view('admin.agenda.create', compact('profesionales', 'servicios', 'clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'servicio_id' => 'required|exists:servicios,id',
            'profesional_id' => 'required|exists:profesionales,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
            'estado' => 'nullable|in:pendiente,confirmada,completada,cancelada',
            'notas' => 'nullable|string'
        ]);

        // Si no se proporciona estado, usar 'pendiente'
        if (!isset($data['estado'])) {
            $data['estado'] = 'pendiente';
        }

        $cita = Agenda::create($data);
        
        if ($request->expectsJson()) {
            return response()->json($cita);
        }

        return redirect()->route('admin.agenda.index')
            ->with('success', 'Cita creada correctamente.');
    }

    /**
     * Reprogramar cita (respuesta a eventDrop)
     */
    public function updateEvent(Request $request, Agenda $agenda)
    {
        $data = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after:start'
        ]);

        $agenda->update([
            'fecha' => Carbon::parse($data['start'])->toDateString(),
            'hora_inicio' => Carbon::parse($data['start'])->format('H:i:s'),
            'hora_fin' => Carbon::parse($data['end'])->format('H:i:s')
        ]);

        return response()->json(['message' => 'Cita reprogramada correctamente']);
    }

    /**
     * Crear bloqueo (día no disponible)
     */
    public function blocksStore(Request $request)
    {
        $data = $request->validate([
            'fecha' => 'required|date',
            'motivo' => 'nullable|string|max:255',
            'profesional_id' => 'nullable|exists:profesionales,id',
        ]);

        $exists = Bloqueo::where('fecha', $data['fecha'])
                        ->when($data['profesional_id'], function($query) use ($data) {
                            return $query->where('profesional_id', $data['profesional_id']);
                        })
                        ->exists();

        if ($exists) {
            return response()->json([
                'message' => $data['profesional_id'] ? 
                    'La fecha ya está bloqueada para este profesional.' : 
                    'La fecha ya está bloqueada para todos los profesionales.'
            ], 422);
        }

        $bloqueo = Bloqueo::create([
            'fecha' => $data['fecha'],
            'motivo' => $data['motivo'] ?? null,
            'profesional_id' => $data['profesional_id']
        ]);

        return response()->json($bloqueo);
    }

    /**
     * Eliminar bloqueo
     */
    public function blocksDestroy($id)
    {
        $bloqueo = Bloqueo::findOrFail($id);
        $bloqueo->delete();
        return response()->json(['message' => 'Bloqueo eliminado correctamente']);
    }

    /**
     * Mostrar modal de detalles de cita
     */
    public function showModal(Agenda $agenda)
    {
        $agenda->load('cliente', 'profesional', 'servicio');
        return view('admin.agenda.partials.modal-detalle-cita', compact('agenda'));
    }
}