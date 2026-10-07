<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bloqueo extends Model
{
    use HasFactory;

    protected $table = 'bloqueos';

    protected $fillable = [
        'fecha',
        'profesional_id',
        'motivo'
    ];

    public function profesional()
    {
        return $this->belongsTo(Profesional::class);
    }

    protected $casts = [
        'fecha' => 'date',
    ];
}
