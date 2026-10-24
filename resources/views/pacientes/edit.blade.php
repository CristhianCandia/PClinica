@extends('layouts.' . (Auth::user()->rol->nombre === 'RECEPCIONISTA' ? 'recepcionista' : 'app'))

@section('title', 'Editar Paciente')

@section('content')
<div class="content-header">
    <h1>Editar paciente</h1>
    <p>Modificar datos de: {{ $paciente->nombres }} {{ $paciente->apellidos }}</p>
</div>

@if($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
    <form method="POST" action="{{ route(Auth::user()->rol->nombre === 'RECEPCIONISTA' ? 'recepcionista.pacientes.update' : 'admin.pacientes.update', $paciente->id_paciente) }}">
        @csrf
        @method('PUT')
        
        <h3 style="margin-bottom: 20px; color: #1e5799; font-size: 16px;">Datos personales</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Código *</label>
                <input type="text" name="codigo" value="{{ $paciente->codigo }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">CI *</label>
                <input type="text" name="ci" value="{{ $paciente->ci }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Nombres *</label>
                <input type="text" name="nombres" value="{{ $paciente->nombres }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Apellidos *</label>
                <input type="text" name="apellidos" value="{{ $paciente->apellidos }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Fecha de nacimiento *</label>
                <input type="date" name="fecha_nacimiento" value="{{ $paciente->fecha_nacimiento }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Sexo *</label>
                <select name="sexo" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background: white;">
                    <option value="M" {{ $paciente->sexo == 'M' ? 'selected' : '' }}>Masculino</option>
                    <option value="F" {{ $paciente->sexo == 'F' ? 'selected' : '' }}>Femenino</option>
                    <option value="O" {{ $paciente->sexo == 'O' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Teléfono *</label>
                <input type="text" name="telefono" value="{{ $paciente->telefono }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Correo electrónico</label>
                <input type="email" name="correo" value="{{ $paciente->correo }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div style="grid-column: 1 / -1;">
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Dirección</label>
                <input type="text" name="direccion" value="{{ $paciente->direccion }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
        </div>

        <h3 style="margin-bottom: 20px; color: #1e5799; font-size: 16px;">Información médica</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Tipo de sangre</label>
                <select name="tipo_sangre" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background: white;">
                    <option value="">Seleccione...</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $tipo)
                        <option value="{{ $tipo }}" {{ $paciente->tipo_sangre == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                    @endforeach
                </select>
            </div>
            <div style="grid-column: 1 / -1;">
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Alergias</label>
                <textarea name="alergias" rows="2" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">{{ $paciente->alergias }}</textarea>
            </div>
            <div style="grid-column: 1 / -1;">
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Enfermedades crónicas</label>
                <textarea name="enfermedades_cronicas" rows="2" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">{{ $paciente->enfermedades_cronicas }}</textarea>
            </div>
            <div style="grid-column: 1 / -1;">
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Medicamentos</label>
                <textarea name="medicamentos" rows="2" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">{{ $paciente->medicamentos }}</textarea>
            </div>
            <div style="grid-column: 1 / -1;">
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Antecedentes</label>
                <textarea name="antecedentes" rows="2" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">{{ $paciente->antecedentes }}</textarea>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" style="padding: 12px 30px; background: #1e5799; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: 600; margin-right: 10px;">Actualizar paciente</button>
            <a href="{{ route(Auth::user()->rol->nombre === 'RECEPCIONISTA' ? 'recepcionista.pacientes' : 'admin.pacientes') }}" style="padding: 12px 30px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; font-size: 14px;">Cancelar</a>
        </div>
    </form>
</div>
@endsection