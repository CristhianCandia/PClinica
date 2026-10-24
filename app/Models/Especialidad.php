<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'especialidad';
    protected $primaryKey = 'id_especialidad';
    public $timestamps = false;
    
    protected $fillable = ['nombre', 'descripcion', 'estado'];

    // ⚠️ ESTA ES LA FUNCIÓN QUE FALTABA Y CAUSABA EL ERROR
    public function medicos()
    {
        return $this->belongsToMany(Medico::class, 'medico_especialidad', 'id_especialidad', 'id_medico');
    }
}