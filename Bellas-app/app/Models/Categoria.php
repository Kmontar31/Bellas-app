<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = ['nombre', 'nombre_categoria'];

    /**
     * Una Categoria tiene muchos Servicios
     */
    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }
}
