@extends('layouts.recepcionista')

@section('title', 'Consultar Disponibilidad de Citas')

@section('content')
<div class="content-header">
    <h1>Consultar disponibilidad de citas</h1>
    <p>Seleccione la especialidad, el médico y la fecha</p>
</div>

<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 30px;">
    <form id="formDisponibilidad" style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
        @csrf
        <div>
            <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Especialidad</label>
            <select id="id_especialidad" name="id_especialidad" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; background: white;">
                <option value="">Todas</option>
                @foreach($especialidades as $esp)
                    <option value="{{ $esp->id_especialidad }}">{{ $esp->nombre }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Médico</label>
            <select id="id_medico" name="id_medico" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; background: white;">
                <option value="">Seleccione...</option>
                @foreach($medicos as $med)
                    <option value="{{ $med->id_medico }}" data-especialidades="{{ $med->especialidades->pluck('id_especialidad')->join(',') }}">
                        {{ $med->nombres }} {{ $med->apellidos }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Fecha</label>
            <input type="date" id="fecha" name="fecha" required min="{{ date('Y-m-d') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
        </div>
        
        <div>
            <button type="button" onclick="consultarDisponibilidad()" style="padding: 10px 25px; background: #1e5799; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: 600;">
                Consultar
            </button>
        </div>
    </form>
</div>

<div id="resultadoDisponibilidad" style="display: none;">
    <h3 style="margin-bottom: 15px; color: #333; font-size: 16px;">
        Horarios disponibles — <span id="fechaResultado"></span>
    </h3>
    <p style="color: #666; font-size: 14px; margin-bottom: 15px;">Haga clic en una hora para programar la cita</p>
    
    <div id="horariosContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 10px;">
        <!-- Los horarios se cargarán aquí -->
    </div>
</div>

<script>
// Filtrar médicos por especialidad
document.getElementById('id_especialidad').addEventListener('change', function() {
    const especialidadId = this.value;
    const medicoSelect = document.getElementById('id_medico');
    const options = medicoSelect.querySelectorAll('option');
    
    options.forEach(option => {
        if (option.value === '') return;
        
        const especialidades = option.dataset.especialidades.split(',');
        if (especialidadId === '' || especialidades.includes(especialidadId)) {
            option.style.display = 'block';
        } else {
            option.style.display = 'none';
        }
    });
    
    medicoSelect.value = '';
});

function consultarDisponibilidad() {
    const idMedico = document.getElementById('id_medico').value;
    const fecha = document.getElementById('fecha').value;
    
    if (!idMedico || !fecha) {
        alert('Por favor seleccione un médico y una fecha');
        return;
    }
    
    fetch('/citas/horarios-disponibles', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({
            id_medico: idMedico,
            fecha: fecha
        })
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('horariosContainer');
        const resultadoDiv = document.getElementById('resultadoDisponibilidad');
        const fechaResultado = document.getElementById('fechaResultado');
        
        container.innerHTML = '';
        
        if (data.horarios.length === 0) {
            container.innerHTML = '<p style="color: #999; grid-column: 1/-1; text-align: center; padding: 20px;">No hay horarios disponibles para esta fecha</p>';
        } else {
            data.horarios.forEach(hora => {
                const btn = document.createElement('button');
                btn.textContent = hora;
                btn.style.cssText = 'padding: 10px; background: #e8f0fe; border: 1px solid #1e5799; border-radius: 5px; cursor: pointer; font-size: 14px; transition: all 0.2s;';
                btn.onmouseover = function() { this.style.background = '#1e5799'; this.style.color = 'white'; };
                btn.onmouseout = function() { this.style.background = '#e8f0fe'; this.style.color = 'inherit'; };
                btn.onclick = function() { 
                    window.location.href = `/citas/programar?medico=${idMedico}&fecha=${fecha}&hora=${hora}`;
                };
                container.appendChild(btn);
            });
        }
        
        const fechaObj = new Date(fecha);
        const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        fechaResultado.textContent = `${fechaObj.toLocaleDateString()} (${dias[fechaObj.getDay()]})`;
        
        resultadoDiv.style.display = 'block';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al consultar la disponibilidad');
    });
}
</script>
@endsection