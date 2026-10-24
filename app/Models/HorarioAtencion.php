<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioAtencion extends Model
{
    protected $table = 'horario_atencion';
    protected $primaryKey = 'id_horario';
    public $timestamps = false;
    
    protected $fillable = [
        'id_medico', 'dia_semana', 'hora_inicio', 'hora_fin',
        'duracion_cita_min', 'estado'
    ];
    
    
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'id_medico');
    }
}