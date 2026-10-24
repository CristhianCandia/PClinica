<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'cita';
    protected $primaryKey = 'id_cita';
    public $timestamps = false;
    
    protected $fillable = [
        'codigo', 'id_paciente', 'id_medico', 'id_especialidad',
        'fecha', 'hora_inicio', 'hora_fin', 'motivo', 'estado',
        'motivo_cancelacion', 'observacion_consulta', 'id_usuario_registro',
        'fecha_creacion', 'fecha_actualizacion',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class, 'id_medico');
    }

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'id_especialidad');
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_registro');
    }
}