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
            'categoria' => 'required|string',
            'servicio_id' => 'required|exists:servicios,id',
            'profesional_id' => 'required|exists:profesionales,id',
            'message' => 'nullable|string'
        ]);

        // Find or create cliente
        $cliente = Cliente::firstOrCreate(
            ['email' => $data['email']],
            ['nombre' => $data['name'], 'telefono' => $data['phone'] ?? null]
        );

        $servicio = Servicio::findOrFail($data['servicio_id']);

        // Compute start and end datetimes
        $start = Carbon::parse($data['fecha'] . ' ' . $data['hora']);
        $end = (clone $start)->addMinutes($servicio->duracion_minutos ?? 60);

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

        return redirect()->route('agendar.form')->with('success', 'Reserva creada correctamente. Te contactaremos para confirmar.');
    }

    /**
     * Return services by category (AJAX)
     */
    public function servicesByCategory(Request $request)
    {
        $categoryName = $request->query('categoria');
        // Find the categoria by nombre, then get its servicios
        $categoria = Categoria::where('nombre', $categoryName)->first();
        if (!$categoria) {
            return response()->json([]);
        }
        $services = $categoria->servicios()->get(['id','nombre','duracion_minutos','precio','descripcion']);
        return response()->json($services);
    }

    /**
     * Check availability for a profesional at given datetime
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'profesional_id' => 'required|exists:profesionales,id',
            'fecha' => 'required|date',
            'hora' => 'required'
        ]);

        $profesionalId = $request->query('profesional_id');
        $fecha = $request->query('fecha');
        $hora = $request->query('hora');
        $servicioId = $request->query('servicio_id');

        $servicio = $servicioId ? Servicio::find($servicioId) : null;
        $start = Carbon::parse($fecha . ' ' . $hora);
        $end = $servicio ? (clone $start)->addMinutes($servicio->duracion_minutos ?? 60) : (clone $start)->addMinutes(60);

        $conflict = Agenda::where('profesional_id', $profesionalId)
            ->where('fecha', $start->toDateString())
            ->where(function($q) use ($start, $end) {
                $q->where('hora_inicio', '<', $end->format('H:i:s'))
                  ->where('hora_fin', '>', $start->format('H:i:s'));
            })->exists();

        return response()->json(['available' => !$conflict]);
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
            $citaStart = Carbon::parse($cita->fecha->format('Y-m-d') . ' ' . $cita->hora_inicio);
            $citaEnd = Carbon::parse($cita->fecha->format('Y-m-d') . ' ' . $cita->hora_fin);

            $events[] = [
                'id' => $cita->id,
                'title' => ($cita->cliente ? $cita->cliente->nombre . ' - ' : '') . 
                          ($cita->servicio ? $cita->servicio->nombre : 'Cita'),
                'start' => $citaStart->toIso8601String(),
                'end' => $citaEnd->toIso8601String(),
                'extendedProps' => [
                    'profesional' => optional($cita->profesional)->nombre,
                    'servicio' => optional($cita->servicio)->nombre,
                    'estado' => $cita->estado ?? null,
                    'tipo' => 'cita',
                    'cliente' => optional($cita->cliente)->nombre
                ],
                'className' => 'ocupado'
            ];
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
                    $events[] = [
                        'title' => 'Disponible - ' . $horario->profesional->nombre,
                        'start' => $currentDate->format('Y-m-d') . ' ' . $horario->hora_inicio,
                        'end' => $currentDate->format('Y-m-d') . ' ' . $horario->hora_fin,
                        'className' => 'disponible',
                        'rendering' => 'background'
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
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'estado' => 'required|in:pendiente,confirmada,cancelada',
            'notas' => 'nullable|string'
        ]);

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
}