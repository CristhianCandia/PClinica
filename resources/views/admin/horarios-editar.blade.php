@extends('layouts.app')

@section('title', 'Editar Horario')

@section('content')
<div class="content-header">
    <h1>Editar horario</h1>
    <p>Médico: {{ $medico->nombres }} {{ $medico->apellidos }}</p>
</div>

@if($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        {{ $errors->first() }}
    </div>
@endif

<div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); max-width: 700px;">
    <form method="POST" action="{{ route('admin.horarios.update', $horario->id_horario) }}">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Día *</label>
                <select name="dia_semana" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; background: white;">
                    <option value="1" {{ $horario->dia_semana == 1 ? 'selected' : '' }}>Lunes</option>
                    <option value="2" {{ $horario->dia_semana == 2 ? 'selected' : '' }}>Martes</option>
                    <option value="3" {{ $horario->dia_semana == 3 ? 'selected' : '' }}>Miércoles</option>
                    <option value="4" {{ $horario->dia_semana == 4 ? 'selected' : '' }}>Jueves</option>
                    <option value="5" {{ $horario->dia_semana == 5 ? 'selected' : '' }}>Viernes</option>
                    <option value="6" {{ $horario->dia_semana == 6 ? 'selected' : '' }}>Sábado</option>
                    <option value="7" {{ $horario->dia_semana == 7 ? 'selected' : '' }}>Domingo</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Hora inicial *</label>
                <input type="time" name="hora_inicio" value="{{ $horario->hora_inicio }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Hora final *</label>
                <input type="time" name="hora_fin" value="{{ $horario->hora_fin }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Duración de cita (min) *</label>
            <input type="number" name="duracion_cita_min" value="{{ $horario->duracion_cita_min }}" min="10" max="120" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="padding: 10px 25px; background: #1e5799; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: 600;">
                Actualizar
            </button>
            <a href="{{ route('admin.horarios.ver', $medico->id_medico) }}" style="padding: 10px 25px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; font-weight: 600;">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection