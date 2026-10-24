<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'paciente';
    protected $primaryKey = 'id_paciente';
    public $timestamps = false;
    
    protected $fillable = [
        'codigo', 'ci', 'nombres', 'apellidos', 'fecha_nacimiento',
        'sexo', 'telefono', 'correo', 'direccion', 'tipo_sangre',
        'alergias', 'enfermedades_cronicas', 'medicamentos', 'antecedentes',
        'id_usuario', 'estado', 'fecha_registro'
    ];
}