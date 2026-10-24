@extends('layouts.recepcionista')

@section('title', 'Programar Cita Médica')

@section('content')
<div class="content-header">
    <h1>Programar cita médica</h1>
    <p>Complete los datos para agendar la cita</p>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); max-width: 800px;">
    <form method="POST" action="{{ route('citas.programar') }}">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Paciente *</label>
                <select name="id_paciente" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; background: white;">
                    <option value="">Seleccione...</option>
                    @foreach($pacientes as $paciente)
                        <option value="{{ $paciente->id_paciente }}" {{ old('id_paciente') == $paciente->id_paciente ? 'selected' : '' }}>
                            {{ $paciente->nombres }} {{ $paciente->apellidos }} ({{ $paciente->ci }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Médico *</label>
                <select id="id_medico" name="id_medico" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; background: white;">
                    <option value="">Seleccione...</option>
                    @foreach($medicos as $medico)
                        <option value="{{ $medico->id_medico }}" data-especialidades="{{ $medico->especialidades->pluck('id_especialidad')->join(',') }}" {{ old('id_medico') == $medico->id_medico ? 'selected' : '' }}>
                            {{ $medico->nombres }} {{ $medico->apellidos }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Especialidad *</label>
            <select id="id_especialidad" name="id_especialidad" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; background: white;">
                <option value="">Seleccione...</option>
                @foreach($especialidades as $esp)
                    <option value="{{ $esp->id_especialidad }}" {{ old('id_especialidad') == $esp->id_especialidad ? 'selected' : '' }}>
                        {{ $esp->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Fecha *</label>
                <input type="date" name="fecha" value="{{ old('fecha', request('fecha')) }}" required min="{{ date('Y-m-d') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Hora inicio *</label>
                <input type="time" name="hora_inicio" value="{{ old('hora_inicio', request('hora')) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Motivo de consulta</label>
            <textarea name="motivo" rows="3" maxlength="200" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; resize: vertical;">{{ old('motivo') }}</textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="padding: 12px 30px; background: #1e5799; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: 600;">
                Programar Cita
            </button>
            <a href="{{ route('citas.disponibilidad') }}" style="padding: 12px 30px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; font-weight: 600;">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
// Filtrar especialidades según el médico seleccionado
document.getElementById('id_medico').addEventListener('change', function() {
    const medicoOption = this.options[this.selectedIndex];
    const especialidadesMedico = medicoOption.dataset.especialidades ? medicoOption.dataset.especialidades.split(',') : [];
    const especialidadSelect = document.getElementById('id_especialidad');
    const options = especialidadSelect.querySelectorAll('option');
    
    options.forEach(option => {
        if (option.value === '') return;
        
        if (especialidadesMedico.length === 0 || especialidadesMedico.includes(option.value)) {
            option.style.display = 'block';
        } else {
            option.style.display = 'none';
        }
    });
    
    especialidadSelect.value = '';
});
</script>
@endsection