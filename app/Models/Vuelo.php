<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vuelo extends Model
{
    use HasFactory;

    protected $fillable = [
        'ciudad-origen',
        'ciudad-destino',
        'fecha_salida',
        'hora_salida',
        'precio'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'fecha_salida' => 'datetime',
    ];
}
