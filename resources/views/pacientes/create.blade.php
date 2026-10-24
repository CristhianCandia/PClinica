@extends('layouts.recepcionista')

@section('title', 'Nuevo Paciente')

@section('content')
<div class="content-header">
    <h1>Nuevo paciente</h1>
    <p>Los campos con * son obligatorios. El CI no puede repetirse.</p>
</div>

@if($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 12px 16px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb; font-size: 14px;">
        {{ $errors->first() }}
    </div>
@endif

<div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); max-width: 900px;">
    <form method="POST" action="{{ route('recepcionista.pacientes.store') }}">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 2fr 2fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">CI *</label>
                <input type="text" name="ci" value="{{ old('ci') }}" required maxlength="15" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Nombres *</label>
                <input type="text" name="nombres" value="{{ old('nombres') }}" required maxlength="60" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Apellidos *</label>
                <input type="text" name="apellidos" value="{{ old('apellidos') }}" required maxlength="60" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Fecha de nacimiento *</label>
                <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Sexo *</label>
                <select name="sexo" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; background: white;">
                    <option value="">Seleccione...</option>
                    <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                    <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                    <option value="O" {{ old('sexo') == 'O' ? 'selected' : '' }}>Otra</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Teléfono *</label>
                <input type="text" name="telefono" value="{{ old('telefono') }}" required maxlength="15" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Correo electrónico</label>
                <input type="email" name="correo" value="{{ old('correo') }}" maxlength="120" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Dirección</label>
                <input type="text" name="direccion" value="{{ old('direccion') }}" maxlength="150" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="padding: 10px 25px; background: #1e5799; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: 600;">Guardar</button>
            <a href="{{ route('recepcionista.pacientes') }}" style="padding: 10px 25px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; font-weight: 600; display: inline-block;">Cancelar</a>
        </div>
    </form>
</div>
@endsection