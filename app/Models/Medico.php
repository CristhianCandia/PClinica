<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    protected $table = 'medico';
    protected $primaryKey = 'id_medico';
    public $timestamps = false;
    
    protected $fillable = [
        'codigo', 'registro_profesional', 'nombres', 'apellidos',
        'telefono', 'correo', 'id_usuario', 'estado', 'fecha_registro'
    ];

    // ⚠️ ESTA ES LA RELACIÓN QUE FALTABA
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    // Relación con especialidades
    public function especialidades()
    {
        return $this->belongsToMany(Especialidad::class, 'medico_especialidad', 'id_medico', 'id_especialidad');
    }
}