<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesional extends Model
{
    use HasFactory;

    protected $table = 'profesionales';

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'especialidad'
    ];

    /**
     * Obtener los horarios del profesional
     */
    public function horarios()
    {
        return $this->hasMany(Horario::class, 'profesional_id');
    }

    /**
     * Obtener las citas del profesional
     */
    public function citas()
    {
        return $this->hasMany(Agenda::class, 'profesional_id');
    }

    /**
     * Obtener los servicios que ofrece el profesional
     */
    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'profesional_servicio')
                    ->withTimestamps();
    }
}
