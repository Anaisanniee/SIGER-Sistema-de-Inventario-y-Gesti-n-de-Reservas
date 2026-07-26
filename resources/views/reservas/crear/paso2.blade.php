@extends('layouts.app')
@section('mostrarPerfil', 'false')
@section('mostrarBusqueda', 'false')
@section('content')
@php
    // Recuperamos el tipo de recurso de la sesión o del controlador
    $tipoRecurso = $tipoRecurso ?? session('reserva.tipo_recurso', 'activo');
    $recurso = $recurso ?? session('reserva.recurso_objeto', null);
@endphp

<link rel="stylesheet" href="{{ asset('css/components/stepper.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/detalle-recurso.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">

<div class="contenedor-reserva-universal">
    
    {{-- 1. COMPONENTE STEPPER (Barra de Progreso en Paso 2) --}}
    <x-reservas.stepper paso="2" />

    {{-- 2. REJILLA DE DISTRIBUCIÓN PRINCIPAL (Grid) --}}
    <div class="dashboard-reserva-grid">
        
        {{-- ==========================================
             COLUMNA IZQUIERDA: CONFIGURACIÓN
             ========================================== --}}
        <div class="columna-formulario">
            
            {{-- Bloque Informativo del Recurso --}}
            <div class="tarjeta-reserva-siger">
                <h3>Recurso Seleccionado</h3>
                <p class="subtitulo-tarjeta">Puedes volver atrás para cambiar el elemento</p>
                
                {{-- COMPONENTE: Detalle del Recurso (Con Estado Disponible) --}}
                <x-reservas.detalle-recurso 
                    :nombre="isset($recurso) && $recurso ? ($recurso->act_nombre ?? ($recurso->aula_nombre ?? ($recurso->nombres ?? 'Recurso Seleccionado'))) : 'Recurso Seleccionado'" 
                    :detalle="isset($recurso) && $recurso ? ($recurso->act_serial ?? (isset($recurso->aula_capacidad) ? 'Capacidad: ' . $recurso->aula_capacidad : 'Detalle no disponible')) : 'Detalle no disponible'" 
                    estado="Disponible" 
                />
            </div>

            {{-- Formulario Dinámico de Reserva --}}
            <form action="{{ route('reservas.paso2.post') }}" method="POST" class="formulario-dinamico">
                @csrf
                
                {{-- Bloque de Fecha y Horario vuelto Desplegable en Móvil --}}
                <details class="tarjeta-reserva-siger acordeon-reserva" open>
                    <summary>
                        <div>
                            <h3>Fecha y Horario</h3>
                            <p class="subtitulo-tarjeta">Selecciona los rangos en los que usarás el recurso</p>
                        </div>
                        <span class="icono-flecha">▼</span>
                    </summary>
                    
                    <div class="contenido-desplegable">
                        {{-- Rejilla de fechas responsiva --}}
                        <div class="grid-dos-columnas">
                            <div class="post-form">
                                <label for="res_fecha_inicio">Fecha de Inicio <span class="text-danger">*</span></label>
                                <input type="date" id="res_fecha_inicio" name="res_fecha_inicio" required 
                                    min="{{ date('Y-m-d') }}"
                                    value="{{ old('res_fecha_inicio') }}">
                            </div>

                            <div class="post-form">
                                <label for="res_fecha_fin">Fecha de Fin <span class="text-danger">*</span></label>
                                <input type="date" id="res_fecha_fin" name="res_fecha_fin" required 
                                    min="{{ date('Y-m-d') }}"
                                    value="{{ old('res_fecha_fin') }}">
                            </div>
                        </div>

                        {{-- Rango de Horas Manuales Pareado --}}
                        <div class="grid-dos-columnas margin-top-main">
                            <div class="post-form">
                                <label for="res_hora_inicio">Hora de Inicio <span class="text-danger">*</span></label>
                                <input type="time" id="res_hora_inicio" name="res_hora_inicio" required 
                                    value="{{ old('res_hora_inicio') }}">
                            </div>

                            <div class="post-form">
                                <label for="res_hora_fin">Hora de Fin <span class="text-danger">*</span></label>
                                <input type="time" id="res_hora_fin" name="res_hora_fin" required 
                                    value="{{ old('res_hora_fin') }}">
                            </div>
                        </div>
                        
                        {{-- Lógica Inteligente de Columnas para Motivo y Aula --}}
                        @if($tipoRecurso !== 'aula')
                            {{-- CASO A: El recurso NO es un aula. Renderiza ambos en DOS columnas --}}
                            <div class="grid-dos-columnas margin-top-main">
                                <div class="post-form">
                                    <label for="res_motivo" class="form-label-siger">Motivo o Justificación de la Reserva <span class="text-danger">*</span></label>
                                    <textarea 
                                        id="res_motivo" 
                                        name="res_motivo" 
                                        class="form-control" 
                                        rows="3" 
                                        style="height: auto !important; min-height: 90px; border-radius: 8px !important; padding: 10px 15px !important; resize: none; outline: none !important; box-shadow: none !important; border: 1px solid var(--color-fondo, #ccc);" 
                                        onfocus="this.style.border='1px solid var(--color-principal, #28a745)'"
                                        onblur="this.style.border='1px solid var(--color-fondo, #ccc)'"
                                        required 
                                        placeholder="Ej. Práctica de laboratorio con estudiantes de décimo..."
                                    >{{ old('res_motivo') }}</textarea>
                                </div>

                                <div class="post-form">
                                    <label for="aula_uso">¿En qué aula o salón utilizará el recurso? <span class="text-danger">*</span></label>
                                    <select 
                                        name="aula_uso" 
                                        id="aula_uso" 
                                        required 
                                        class="input-siger form-control"
                                        style="...">
                                        <option value="" disabled selected>Selecciona un salón...</option>
                                        @foreach($aulas as $aula)
                                            {{-- Aquí cambiamos $aula->aula_nombre por la llave primaria del aula, por ejemplo $aula->id --}}
                                            <option value="{{ $aula->id ?? $aula->aula_id }}" {{ old('aula_uso') == ($aula->id ?? $aula->aula_id) ? 'selected' : '' }}>
                                                {{ $aula->aula_nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @else
                            {{-- CASO B: El recurso SÍ es un aula. Renderiza SOLO el motivo a ANCHO COMPLETO --}}
                            <div class="margin-top-main">
                                <div class="post-form">
                                    <label for="res_motivo" class="form-label-siger">Motivo o Justificación de la Reserva <span class="text-danger">*</span></label>
                                    <textarea 
                                        id="res_motivo" 
                                        name="res_motivo" 
                                        class="form-control" 
                                        rows="3" 
                                        style="height: auto !important; min-height: 90px; border-radius: 8px !important; padding: 10px 15px !important; resize: none; outline: none !important; box-shadow: none !important; border: 1px solid var(--color-fondo, #ccc);" 
                                        onfocus="this.style.border='1px solid var(--color-principal, #28a745)'"
                                        onblur="this.style.border='1px solid var(--color-borde, #ccc)'"
                                        required 
                                        placeholder="Ej. Práctica de laboratorio con estudiantes de décimo..."
                                    >{{ old('res_motivo') }}</textarea>
                                </div>
                            </div>
                        @endif

                        {{-- Mensaje de validación de backend (si existe) --}}
                        @if ($errors->has('res_motivo'))
                            <div class="alert alert-danger mt-2">
                                {{ $errors->first('res_motivo') }}
                            </div>
                        @endif         
                        
                        {{-- Botón de confirmación local --}}
                        <div class="contenedor-botones">
                            <x-botones.boton type="submit" class="btn-siger-accion btn">
                                Confirmar Horario
                            </x-botones.boton>
                        </div>
                    </div>
                </details>
            </form>
        </div>

        {{-- ==========================================
             COLUMNA DERECHA: RESUMEN LATERAL
             ========================================== --}}
        <div class="columna-resumen">
            <div class="tarjeta-reserva-siger resumen-card">
                <h3>Resumen de Reserva</h3>
                
                {{-- Reutilización del Componente (Sin Estado para el resumen) --}}
                <x-reservas.detalle-recurso 
                    :nombre="isset($recurso) && $recurso ? ($recurso->act_nombre ?? ($recurso->aula_nombre ?? ($recurso->nombres ?? 'Recurso Seleccionado'))) : 'Recurso Seleccionado'" 
                    :detalle="isset($recurso) && $recurso ? ($recurso->act_serial ?? (isset($recurso->aula_capacidad) ? 'Capacidad: ' . $recurso->aula_capacidad : 'Detalle no disponible')) : 'Detalle no disponible'" 
                />

                {{-- Tabla de Detalles Requeridos (Con sus respectivos IDs para JS) --}}
                <table class="tabla-resumen-siger">
                    <tr>
                        <td>Fecha</td>
                        <td class="resaltado-amarillo" id="resumen-fecha-preview">Por completar</td>
                    </tr>
                    <tr>
                        <td>Horario</td>
                        <td class="resaltado-amarillo" id="resumen-horario-preview">Por completar</td>
                    </tr>
                    <tr>
                        <td>Docente</td>
                        <td>{{ Auth::user()->nombres ?? 'Docente Solicitante' }}</td>
                    </tr>
                    <tr>
                        <td>Identificación</td>
                        <td class="resaltado-amarillo" id="resumen-identificacion-preview">{{ Auth::user()->cedula ?? Auth::user()->identificacion ?? 'Por completar' }}</td>
                    </tr>
                    <tr>
                        <td>Motivo</td>
                        <td class="resaltado-amarillo" id="resumen-motivo-preview" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Por completar</td>
                    </tr>
                    @if($tipoRecurso !== 'aula')
                    <tr>
                        <td>Aula de uso</td>
                        <td class="resaltado-amarillo" id="resumen-aula-preview">Por completar</td>
                    </tr>
                    @endif
                </table>

                {{-- Alerta Informativa sobre la validación del backend --}}
                <div class="notificacion-alerta-siger">
                    <p>ℹ️ La reserva quedará pendiente de aprobación por el administrador.</p>
                </div>

                {{-- Bloque de botones principales con separación fluida --}}
                <div class="contenedor-botones">                
                    <x-botones.boton type="button" class="btn-siger-accion btn" style="width: 100%;">
                        Confirmar Reserva
                    </x-botones.boton>
                </div>
            </div>
        </div> {{-- Fin Columna Derecha --}}

    </div> {{-- Fin del Grid --}}
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputFechaInicio = document.getElementById('res_fecha_inicio');
    const inputFechaFin = document.getElementById('res_fecha_fin');
    const inputHoraInicio = document.getElementById('res_hora_inicio');
    const inputHoraFin = document.getElementById('res_hora_fin');

    const previewFecha = document.getElementById('resumen-fecha-preview');
    const previewHorario = document.getElementById('resumen-horario-preview');
    const previewIdentificacion = document.getElementById('resumen-identificacion-preview');

    const hoy = "{{ date('Y-m-d') }}";

    // 1. Sincronizar la fecha mínima de fin
    inputFechaInicio.addEventListener('change', function () {
        inputFechaFin.min = this.value;
        if (inputFechaFin.value && inputFechaFin.value < this.value) {
            inputFechaFin.value = this.value;
        }
        actualizarFechaPreview();
    });

    inputFechaFin.addEventListener('change', function () {
        if (inputFechaInicio.value && this.value < inputFechaInicio.value) {
            alert('La fecha de fin no puede ser anterior a la fecha de inicio.');
            this.value = inputFechaInicio.value;
        }
        actualizarFechaPreview();
    });

    function actualizarFechaPreview() {
        if (inputFechaInicio.value && inputFechaFin.value) {
            if (inputFechaInicio.value === inputFechaFin.value) {
                previewFecha.textContent = inputFechaInicio.value;
            } else {
                previewFecha.textContent = `${inputFechaInicio.value} al ${inputFechaFin.value}`;
            }
            previewFecha.classList.remove('resaltado-amarillo');
        } else if (inputFechaInicio.value) {
            previewFecha.textContent = inputFechaInicio.value;
            previewFecha.classList.remove('resaltado-amarillo');
        } else {
            previewFecha.textContent = 'Por completar';
            previewFecha.classList.add('resaltado-amarillo');
        }
    }

    // 2. Asegurar que la hora de fin sea mayor a la hora de inicio y actualizar preview
    inputHoraInicio.addEventListener('change', function () {
        inputHoraFin.min = this.value;
        if (inputHoraFin.value && inputHoraFin.value <= this.value) {
            inputHoraFin.value = '';
        }
        actualizarHorarioPreview();
    });

    inputHoraFin.addEventListener('change', function () {
        if (inputHoraInicio.value && this.value <= inputHoraInicio.value) {
            alert('La hora de fin debe ser posterior a la hora de inicio.');
            this.value = '';
        }
        actualizarHorarioPreview();
    });

    function actualizarHorarioPreview() {
        if (inputHoraInicio.value && inputHoraFin.value) {
            previewHorario.textContent = `${inputHoraInicio.value} - ${inputHoraFin.value}`;
            previewHorario.classList.remove('resaltado-amarillo');
        } else {
            previewHorario.textContent = 'Por completar';
            previewHorario.classList.add('resaltado-amarillo');
        }
    }

    // Auto-completar identificación si el usuario la tiene cargada en sesión
    if (previewIdentificacion && previewIdentificacion.textContent.trim() !== 'Por completar') {
        previewIdentificacion.classList.remove('resaltado-amarillo');
    }
});
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const acordeon = document.querySelector('.acordeon-reserva');

        // Función que evalúa el tamaño de la pantalla
        function verificarResolucion() {
            if (window.innerWidth > 1024) {
                // Si la pantalla es de PC (mayor a 1024px), se fuerza a abrirse siempre
                acordeon.setAttribute('open', 'true');
            }
        }

        // Ejecutar al cargar la página
        verificarResolucion();

        // Escuchar cada vez que se estira o encoge la pantalla (o se rota el dispositivo)
        window.addEventListener('resize', verificarResolucion);

        // =========================================================================
        // ACTUALIZACIÓN EN TIEMPO REAL DEL MOTIVO Y EL AULA EN EL RESUMEN LATERAL
        // =========================================================================
        const txtMotivo = document.getElementById('res_motivo');
        const previewMotivo = document.getElementById('resumen-motivo-preview');
        
        const inputAula = document.getElementById('aula_uso');
        const previewAula = document.getElementById('resumen-aula-preview');

        // Escucha la escritura en el textarea del motivo
        if (txtMotivo && previewMotivo) {
            txtMotivo.addEventListener('input', function() {
                const valor = this.value.trim();
                if (valor !== '') {
                    previewMotivo.textContent = valor;
                    previewMotivo.classList.remove('resaltado-amarillo');
                    previewMotivo.setAttribute('title', valor); // Agrega un tooltip si el texto es muy largo
                } else {
                    previewMotivo.textContent = 'Por completar';
                    previewMotivo.classList.add('resaltado-amarillo');
                    previewMotivo.removeAttribute('title');
                }
            });
        }

        // Escucha el cambio de selección en el select del aula de destino
        if (inputAula && previewAula) {
            inputAula.addEventListener('change', function() {
                const valor = this.value.trim();
                if (valor !== '') {
                    previewAula.textContent = valor;
                    previewAula.classList.remove('resaltado-amarillo');
                } else {
                    previewAula.textContent = 'Por completar';
                    previewAula.classList.add('resaltado-amarillo');
                }
            });
        }
    });
</script>