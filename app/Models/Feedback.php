<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'nombre_usuario',
        'tipo',
        'comentario',
        'estado',
    ];
}
