<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $table = 'agenda';

    protected $fillable = [
        'cliente_id',
        'profesional_id',
        'servicio_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'notas'
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_inicio' => 'datetime',
        'hora_fin' => 'datetime',
    ];

    /**
     * Obtener el cliente de la cita
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Obtener el profesional asignado
     */
    public function profesional()
    {
        return $this->belongsTo(Profesional::class, 'profesional_id');
    }

    /**
     * Obtener el servicio solicitado
     */
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}
