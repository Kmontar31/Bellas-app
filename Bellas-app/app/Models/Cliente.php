<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'direccion'
    ];

    /**
     * Obtener las citas del cliente
     */
    public function citas()
    {
        return $this->hasMany(Agenda::class, 'cliente_id');
    }
}
