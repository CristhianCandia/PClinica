<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'id_rol',
        'nombre_usuario',
        'correo',
        'contrasena_hash',
        'estado',
        'fecha_creacion',
        'ultimo_acceso',
    ];

    protected $hidden = [
        'contrasena_hash',
    ];

    public function getAuthPassword()
    {
        return $this->contrasena_hash;
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }
}