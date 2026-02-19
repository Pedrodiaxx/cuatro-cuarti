<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    protected $fillable = ['name'];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
} //Si la migración que vimos antes era el plano de la base de datos (el SQL), 
//el Modelo es el embajador en PHP. Es la clase que te permite interactuar con esa tabla sin escribir una sola línea de SQL.
