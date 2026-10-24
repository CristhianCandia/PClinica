@extends('layouts.app')

@section('title', 'Gestionar Horarios de Atención')

@section('content')
<div class="content-header">
    <h1>Gestionar horarios de atención</h1>
    <p>Asignar bloques de atención a los médicos</p>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        {{ $errors->first() }}
    </div>
@endif

<!-- SELECTOR DE MÉDICO -->
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 30px;">
    <h3 style="margin-bottom: 15px; color: #333; font-size: 16px;">Médico</h3>
    <div style="display: flex; gap: 10px; align-items: end;">
        <div style="flex: 1;">
            <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Seleccionar médico</label>
            <select id="selectorMedico" onchange="if(this.value) window.location.href='/admin/horarios/medico/' + this.value" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; background: white;">
                <option value="">Seleccione un médico...</option>
                @foreach($medicos as $med)
                    <option value="{{ $med->id_medico }}" {{ isset($medico) && $medico->id_medico == $med->id_medico ? 'selected' : '' }}>
                        {{ $med->nombres }} {{ $med->apellidos }} ({{ $med->codigo }})
                    </option>
                @endforeach
            </select>
        </div>
        <button type="button" onclick="if(document.getElementById('selectorMedico').value) window.location.href='/admin/horarios/medico/' + document.getElementById('selectorMedico').value" style="padding: 10px 25px; background: #1e5799; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: 600;">
            Ver
        </button>
    </div>
</div>

@if(isset($medico) && $medico)
<!-- FORMULARIO NUEVO BLOQUE -->
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 30px;">
    <h3 style="margin-bottom: 20px; color: #333; font-size: 16px;">Nuevo bloque de horario</h3>
    <form method="POST" action="{{ route('admin.horarios.store') }}">
        @csrf
        <input type="hidden" name="id_medico" value="{{ $medico->id_medico }}">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Día *</label>
                <select name="dia_semana" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; background: white;">
                    <option value="">Seleccione...</option>
                    <option value="1">Lunes</option>
                    <option value="2">Martes</option>
                    <option value="3">Miércoles</option>
                    <option value="4">Jueves</option>
                    <option value="5">Viernes</option>
                    <option value="6">Sábado</option>
                    <option value="7">Domingo</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Hora inicial *</label>
                <input type="time" name="hora_inicio" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Hora final *</label>
                <input type="time" name="hora_fin" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Duración de cita (min) *</label>
                <input type="number" name="duracion_cita_min" value="15" min="10" max="120" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
        </div>
        
        <button type="submit" style="margin-top: 20px; padding: 10px 25px; background: #1e5799; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: 600;">
            Guardar
        </button>
    </form>
</div>

<!-- TABLA DE HORARIOS -->
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #e0e0e0;">
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Día</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Hora inicial</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Hora final</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Duración</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Citas por bloque</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Estado</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($horarios as $horario)
            <tr style="border-bottom: 1px solid #e0e0e0;">
                <td style="padding: 12px; font-size: 14px;">
                    @php
                        $dias = [1=>'Lunes', 2=>'Martes', 3=>'Miércoles', 4=>'Jueves', 5=>'Viernes', 6=>'Sábado', 7=>'Domingo'];
                    @endphp
                    {{ $dias[$horario->dia_semana] }}
                </td>
                <td style="padding: 12px; font-size: 14px;">{{ date('H:i', strtotime($horario->hora_inicio)) }}</td>
                <td style="padding: 12px; font-size: 14px;">{{ date('H:i', strtotime($horario->hora_fin)) }}</td>
                <td style="padding: 12px; font-size: 14px;">{{ $horario->duracion_cita_min }} min</td>
                <td style="padding: 12px; font-size: 14px;">
                    @php
                        $totalMinutos = (strtotime($horario->hora_fin) - strtotime($horario->hora_inicio)) / 60;
                        $citasPorBloque = floor($totalMinutos / $horario->duracion_cita_min);
                    @endphp
                    {{ $citasPorBloque }}
                </td>
                <td style="padding: 12px;">
                    @if($horario->estado)
                        <span style="padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #d4edda; color: #155724;">Activo</span>
                    @else
                        <span style="padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #f8d7da; color: #721c24;">Inactivo</span>
                    @endif
                </td>
                <td style="padding: 12px;">
                    <a href="{{ route('admin.horarios.editar', $horario->id_horario) }}" 
                       style="padding: 6px 12px; background: #17a2b8; color: white; text-decoration: none; border-radius: 4px; font-size: 12px; margin-right: 5px; display: inline-block;">
                        Editar
                    </a>
                    
                    <form method="POST" action="{{ route('admin.horarios.toggle', $horario->id_horario) }}" style="display: inline;">
                        @csrf
                        @if($horario->estado)
                            <button type="submit" style="padding: 6px 12px; background: #dc3545; color: white; border: none; border-radius: 4px; font-size: 12px; cursor: pointer;">
                                Desactivar
                            </button>
                        @else
                            <button type="submit" style="padding: 6px 12px; background: #28a745; color: white; border: none; border-radius: 4px; font-size: 12px; cursor: pointer;">
                                Activar
                            </button>
                        @endif
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding: 20px; text-align: center; color: #666; font-size: 14px;">
                    No hay horarios registrados para este médico.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endif
@endsection