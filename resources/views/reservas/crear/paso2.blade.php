@extends('layouts.app')
@section('mostrarPerfil', 'false')
@section('mostrarBusqueda', 'false')
@section('content')
@php
    // Intentamos obtener el objeto de la sesión
    $recurso = $recurso ?? session('reserva.recurso_objeto', null);
    
    // Determinamos el tipo real basándonos en si el objeto tiene campos de aula o si la sesión lo indica
    $tipoRecurso = session('reserva.tipo_recurso');
    if (!$tipoRecurso && $recurso) {
        $tipoRecurso = isset($recurso->aula_nombre) ? 'aula' : 'activo';
    }
    $tipoRecurso = $tipoRecurso ?? 'activo';

    // Extraer de forma segura fechas y horas separadas si ya existen en la sesión
    $sessionFechaInicio = session('reserva.res_fecha_inicio', '');
    $sessionFechaFin    = session('reserva.res_fecha_fin', '');

    $valFechaInicio = $sessionFechaInicio ? explode(' ', $sessionFechaInicio)[0] : '';
    $valHoraInicio  = ($sessionFechaInicio && isset(explode(' ', $sessionFechaInicio)[1])) ? Str::substr(explode(' ', $sessionFechaInicio)[1], 0, 5) : '';

    $valFechaFin    = $sessionFechaFin ? explode(' ', $sessionFechaFin)[0] : '';
    $valHoraFin     = ($sessionFechaFin && isset(explode(' ', $sessionFechaFin)[1])) ? Str::substr(explode(' ', $sessionFechaFin)[1], 0, 5) : '';
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
            <form action="{{ route('reservas.paso2.post') }}" method="POST" class="formulario-dinamico" id="form-reserva-paso2">
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
                                    value="{{ old('res_fecha_inicio', $valFechaInicio) }}">
                            </div>

                            <div class="post-form">
                                <label for="res_fecha_fin">Fecha de Fin <span class="text-danger">*</span></label>
                                <input type="date" id="res_fecha_fin" name="res_fecha_fin" required 
                                    min="{{ date('Y-m-d') }}"
                                    value="{{ old('res_fecha_fin', $valFechaFin) }}">
                            </div>
                        </div>

                        {{-- Rango de Horas Manuales Pareado --}}
                        <div class="grid-dos-columnas margin-top-main">
                            <div class="post-form">
                                <label for="res_hora_inicio">Hora de Inicio <span class="text-danger">*</span></label>
                                <input type="time" id="res_hora_inicio" name="res_hora_inicio" required 
                                    value="{{ old('res_hora_inicio', $valHoraInicio) }}">
                            </div>

                            <div class="post-form">
                                <label for="res_hora_fin">Hora de Fin <span class="text-danger">*</span></label>
                                <input type="time" id="res_hora_fin" name="res_hora_fin" required 
                                    value="{{ old('res_hora_fin', $valHoraFin) }}">
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
                                    >{{ old('res_motivo', session('reserva.res_motivo')) }}</textarea>
                                </div>

                                <div class="post-form">
                                    <label for="aula_uso">¿En qué aula o salón utilizará el recurso? <span class="text-danger">*</span></label>
                                    <select 
                                        name="aula_uso" 
                                        id="aula_uso" 
                                        required 
                                        class="input-siger form-control">
                                        <option value="" disabled {{ session('reserva.aula_uso') ? '' : 'selected' }}>Selecciona un salón...</option>
                                        @foreach($aulas as $aula)
                                            <option value="{{ $aula->id ?? $aula->aula_id }}" {{ old('aula_uso', session('reserva.aula_uso')) == ($aula->id ?? $aula->aula_id) ? 'selected' : '' }}>
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
                                    >{{ old('res_motivo', session('reserva.res_motivo')) }}</textarea>
                                </div>
                            </div>
                        @endif

                        {{-- Mensaje de validación de backend (si existe) --}}
                        @if ($errors->any())
                            <div class="alert alert-danger mt-2">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
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
                    :nombre="isset($recurso) && $recurso ? ($recurso->aula_nombre ?? $recurso->act_nombre ?? 'Recurso Seleccionado') : 'Recurso Seleccionado'" 
                    :detalle="isset($recurso) && $recurso ? (isset($recurso->aula_capacidad) ? 'Capacidad: ' . $recurso->aula_capacidad : ($recurso->act_serial ?? 'Detalle no disponible')) : 'Detalle no disponible'" 
                    estado="Disponible" 
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
                    <x-botones.boton type="button" id="btn-confirmar-reserva-global" class="btn-siger-accion btn" style="width: 100%;">
                        Confirmar Reserva
                    </x-botones.boton>
                </div>
            </div>
        </div> {{-- Fin Columna Derecha --}}

    </div> {{-- Fin del Grid --}}
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputFechaInicio = document.getElementById('res_fecha_inicio');
    const inputFechaFin = document.getElementById('res_fecha_fin');
    const inputHoraInicio = document.getElementById('res_hora_inicio');
    const inputHoraFin = document.getElementById('res_hora_fin');

    const previewFecha = document.getElementById('resumen-fecha-preview');
    const previewHorario = document.getElementById('resumen-horario-preview');
    const previewIdentificacion = document.getElementById('resumen-identificacion-preview');

    // 1. Sincronizar y actualizar Fecha en tiempo real
    if (inputFechaInicio && inputFechaFin) {
        inputFechaInicio.addEventListener('change', function () {
            inputFechaFin.min = this.value;
            if (inputFechaFin.value && inputFechaFin.value < this.value) {
                inputFechaFin.value = this.value;
            }
            actualizarFechaPreview();
            validarRangoHoras(); // Revalidar horas si cambia la fecha
        });

        inputFechaFin.addEventListener('change', function () {
            if (inputFechaInicio.value && this.value < inputFechaInicio.value) {
                alert('La fecha de fin no puede ser anterior a la fecha de inicio.');
                this.value = inputFechaInicio.value;
            }
            actualizarFechaPreview();
            validarRangoHoras(); // Revalidar horas si cambia la fecha
        });
        
        actualizarFechaPreview();
    }

    function actualizarFechaPreview() {
        if (!previewFecha) return;
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

    // 2. Sincronizar y validar Horario en tiempo real
    function validarRangoHoras() {
        if (!inputHoraInicio.value || !inputHoraFin.value) return;

        // Si las fechas son exactamente iguales, la hora fin DEBE ser mayor a la hora inicio
        if (inputFechaInicio.value && inputFechaFin.value && inputFechaInicio.value === inputFechaFin.value) {
            if (inputHoraFin.value <= inputHoraInicio.value) {
                alert('La hora de fin debe ser mayor a la hora de inicio cuando la reserva es el mismo día.');
                inputHoraFin.value = ''; // Limpiamos la hora inválida
            }
        }
    }

    if (inputHoraInicio && inputHoraFin) {
        inputHoraInicio.addEventListener('change', function () {
            inputHoraFin.min = this.value;
            validarRangoHoras();
            actualizarHorarioPreview();
        });

        inputHoraFin.addEventListener('change', function () {
            validarRangoHoras();
            actualizarHorarioPreview();
        });

        inputHoraInicio.addEventListener('input', actualizarHorarioPreview);
        inputHoraFin.addEventListener('input', actualizarHorarioPreview);

        // Validación inicial por si viene de sesión
        if (inputHoraInicio.value && inputHoraFin.value) {
            validarRangoHoras();
            actualizarHorarioPreview();
        }
    }

    function actualizarHorarioPreview() {
        if (!previewHorario) return;
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

    // 3. Manejo del acordeón según resolución de pantalla
    const acordeon = document.querySelector('.acordeon-reserva');
    function verificarResolucion() {
        if (acordeon && window.innerWidth > 1024) {
            acordeon.setAttribute('open', 'true');
        }
    }
    verificarResolucion();
    window.addEventListener('resize', verificarResolucion);

    // 4. Actualización en tiempo real del motivo y el aula en el resumen lateral
    const txtMotivo = document.getElementById('res_motivo');
    const previewMotivo = document.getElementById('resumen-motivo-preview');
    
    const inputAula = document.getElementById('aula_uso');
    const previewAula = document.getElementById('resumen-aula-preview');

    if (txtMotivo && previewMotivo) {
        if (txtMotivo.value.trim() !== '') {
            previewMotivo.textContent = txtMotivo.value.trim();
            previewMotivo.classList.remove('resaltado-amarillo');
            previewMotivo.setAttribute('title', txtMotivo.value.trim());
        }

        txtMotivo.addEventListener('input', function() {
            const valor = this.value.trim();
            if (valor !== '') {
                previewMotivo.textContent = valor;
                previewMotivo.classList.remove('resaltado-amarillo');
                previewMotivo.setAttribute('title', valor);
            } else {
                previewMotivo.textContent = 'Por completar';
                previewMotivo.classList.add('resaltado-amarillo');
                previewMotivo.removeAttribute('title');
            }
        });
    }

    if (inputAula && previewAula) {
        if (inputAula.value !== '') {
            const selectedOpt = inputAula.options[inputAula.selectedIndex];
            if (selectedOpt) {
                previewAula.textContent = selectedOpt.text.trim();
                previewAula.classList.remove('resaltado-amarillo');
            }
        }

        inputAula.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const valorTexto = selectedOption ? selectedOption.text.trim() : '';
            
            if (this.value !== '') {
                previewAula.textContent = valorTexto;
                previewAula.classList.remove('resaltado-amarillo');
            } else {
                previewAula.textContent = 'Por completar';
                previewAula.classList.add('resaltado-amarillo');
            }
        });
    }

    // 5. Conectar el botón inferior del resumen lateral
    const btnConfirmarGlobal = document.getElementById('btn-confirmar-reserva-global');
    const formReserva = document.getElementById('form-reserva-paso2');
    
    if (btnConfirmarGlobal && formReserva) {
        btnConfirmarGlobal.addEventListener('click', function() {
            if (typeof formReserva.requestSubmit === 'function') {
                formReserva.requestSubmit();
            } else {
                formReserva.submit();
            }
        });
    }
});
</script>
@endsection