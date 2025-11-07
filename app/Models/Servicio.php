<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'duracion_minutos',
        'precio',
        'categoria'
    ];

    /**
     * Obtener los profesionales que ofrecen este servicio
     */
    public function profesionales()
    {
        return $this->belongsToMany(Profesional::class, 'profesional_servicio')
                    ->withTimestamps();
    }

    /**
     * Obtener las citas para este servicio
     */
    public function citas()
    {
        return $this->hasMany(Agenda::class, 'servicio_id');
    }
}
