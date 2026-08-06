@extends('layouts.app')
@section('mostrarPerfil', 'false')
@section('mostrarBusqueda', 'false')
@section('content')
@php
    // 1. Respaldo robusto para recuperar los recursos seleccionados
    $recursosBrutos = session('reserva.recursos_objetos', collect());
    
    $recursosColeccion = collect($recursosBrutos)->map(function($item) {
        return (object) $item;
    });

    if ($recursosColeccion->isEmpty()) {
        $recursoIndividual = session('reserva.recurso_objeto');
        if ($recursoIndividual) {
            $recursosColeccion->push((object) $recursoIndividual);
        } else {
            $recursosColeccion->push((object)[
                'act_nombre' => 'Recurso Seleccionado',
                'act_serial' => 'Serial por definir',
                'aula_nombre' => null,
                'aula_capacidad' => null
            ]);
        }
    }
    
    // 2. Verificación estricta: Mostrar el campo de aula solo si TODOS los elementos son activos puros
    $tipoSession = session('reserva.tipo_recurso');
    
    $soloActivos = $recursosColeccion->every(function($item) {
        $esActivo = isset($item->act_nombre) || isset($item->act_serial);
        $esAula = isset($item->aula_nombre) || isset($item->aula_capacidad);
        return $esActivo && !$esAula;
    });

    $mostrarCampoAula = ($tipoSession === 'activo') || $soloActivos;

    // 3. Extraer automáticamente el ID del aula si es una reserva mixta o de aula
    $aulaAutomaticaId = '';
    if (!$mostrarCampoAula) {
        $aulaEncontrada = $recursosColeccion->first(function($item) {
            return isset($item->id) || isset($item->aula_id);
        });
        if ($aulaEncontrada) {
            $aulaAutomaticaId = $aulaEncontrada->id ?? ($aulaEncontrada->aula_id ?? '');
        }
    }

    // 4. Extraer de forma segura fechas y horas separadas si ya existen en la sesión
    $sessionFechaInicio = session('reserva.res_fecha_inicio', '');
    $sessionFechaFin    = session('reserva.res_fecha_fin', '');

    $valFechaInicio = $sessionFechaInicio ? explode(' ', $sessionFechaInicio)[0] : '';
    $valHoraInicio  = ($sessionFechaInicio && isset(explode(' ', $sessionFechaInicio)[1])) ? Str::substr(explode(' ', $sessionFechaInicio)[1], 0, 5) : '';

    $valFechaFin    = $sessionFechaFin ? explode(' ', $sessionFechaFin)[0] : '';
    $valHoraFin     = ($sessionFechaFin && isset(explode(' ', $sessionFechaFin)[1])) ? Str::substr(explode(' ', $sessionFechaFin)[1], 0, 5) : '';
    
    // 5. Datos seguros del usuario autenticado
    $user = Auth::user();
    $userName = $user->nombres ?? ($user->name ?? 'Docente Solicitante');
    $userCedula = $user->cedula ?? ($user->identificacion ?? ($user->id ?? 'Por completar'));
@endphp

<link rel="stylesheet" href="{{ asset('css/components/stepper.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/detalle-recurso.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">

<div class="contenedor-reserva-universal">
    
    {{-- 1. COMPONENTE STEPPER (Barra de Progreso en Paso 2) --}}
    <x-reservas.stepper paso="2" />

    {{-- 2. FORMULARIO GLOBAL QUE ENVÍA TODO AL CONTROLADOR (guardarPaso2) --}}
    <form action="{{ route('reservas.paso2.post') }}" method="POST" class="formulario-dinamico" id="form-reserva-paso2">
        @csrf
        
        {{-- REJILLA DE DISTRIBUCIÓN PRINCIPAL (Grid) --}}
        <div class="dashboard-reserva-grid">
            
            {{-- ==========================================
                 COLUMNA IZQUIERDA: CONFIGURACIÓN
                 ========================================== --}}
            <div class="columna-formulario">
                
                {{-- Bloque Informativo de Recursos Seleccionados --}}
                <div class="tarjeta-reserva-siger">
                    <h3>Recursos Seleccionados</h3>
                    <p class="subtitulo-tarjeta">Puedes volver atrás para cambiar los elementos</p>
                    
                    @foreach($recursosColeccion as $itemRecurso)
                        <div style="margin-bottom: 10px;">
                            <x-reservas.detalle-recurso 
                                :nombre="$itemRecurso->act_nombre ?? ($itemRecurso->aula_nombre ?? 'Recurso Seleccionado')" 
                                :detalle="$itemRecurso->act_serial ?? (isset($itemRecurso->aula_capacidad) ? 'Capacidad: ' . $itemRecurso->aula_capacidad : 'Detalle no disponible')" 
                                estado="Disponible" 
                            />
                        </div>
                    @endforeach
                </div>

                {{-- Bloque de Fecha y Horario --}}
                <details class="tarjeta-reserva-siger acordeon-reserva" open>
                    <summary>
                        <div>
                            <h3>Fecha y Horario</h3>
                            <p class="subtitulo-tarjeta">Selecciona los rangos en los que usarás el recurso</p>
                        </div>
                        <span class="icono-flecha">▼</span>
                    </summary>
                    
                    <div class="contenido-desplegable">
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
                        
                        {{-- Distribución de Motivo y Aula Condicionada --}}
                        @if($mostrarCampoAula)
                            <div class="grid-dos-columnas margin-top-main">
                                <div class="post-form">
                                    <label for="res_motivo" class="form-label-siger">Motivo o Justificación de la Reserva <span class="text-danger">*</span></label>
                                    <textarea 
                                        id="res_motivo" 
                                        name="res_motivo" 
                                        class="form-control" 
                                        rows="3" 
                                        style="height: auto !important; min-height: 90px; border-radius: 8px !important; padding: 10px 15px !important; resize: none; outline: none !important; box-shadow: none !important; border: 1px solid var(--color-fondo, #ccc);" 
                                        required 
                                        placeholder="Ej. Práctica de laboratorio con estudiantes..."
                                    >{{ old('res_motivo', session('reserva.res_motivo')) }}</textarea>
                                </div>

                                <div class="post-form">
                                    <label for="aula_uso">¿En qué aula o salón utilizará el recurso? <span class="text-danger">*</span></label>
                                    <select name="aula_uso" id="aula_uso" required class="input-siger form-control">
                                        <option value="" disabled {{ session('reserva.aula_uso') ? '' : 'selected' }}>Selecciona un salón...</option>
                                        @foreach($aulas ?? [] as $aula)
                                            <option value="{{ $aula->id ?? $aula->aula_id }}" {{ old('aula_uso', session('reserva.aula_uso')) == ($aula->id ?? $aula->aula_id) ? 'selected' : '' }}>
                                                {{ $aula->aula_nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @else
                            <div class="margin-top-main">
                                <div class="post-form">
                                    <label for="res_motivo" class="form-label-siger">Motivo o Justificación de la Reserva <span class="text-danger">*</span></label>
                                    <textarea 
                                        id="res_motivo" 
                                        name="res_motivo" 
                                        class="form-control" 
                                        rows="3" 
                                        style="height: auto !important; min-height: 90px; border-radius: 8px !important; padding: 10px 15px !important; resize: none; outline: none !important; box-shadow: none !important; border: 1px solid var(--color-fondo, #ccc);" 
                                        required 
                                        placeholder="Ej. Práctica de laboratorio con estudiantes..."
                                    >{{ old('res_motivo', session('reserva.res_motivo')) }}</textarea>
                                </div>
                            </div>
                            {{-- Input oculto que envía automáticamente el ID del aula en reservas mixtas --}}
                            <input type="hidden" name="aula_uso" value="{{ old('aula_uso', $aulaAutomaticaId) }}">
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger mt-2">
                                <ul style="margin: 0; padding-left: 15px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif        
                    </div>
                </details>
            </div>

            {{-- ==========================================
                 COLUMNA DERECHA: RESUMEN LATERAL
                 ========================================== --}}
            <div class="columna-resumen">
                <div class="tarjeta-reserva-siger resumen-card">
                    <h3>Resumen de Reserva</h3>
                    
                    <div style="margin-bottom: 15px;">
                        @foreach($recursosColeccion as $itemRecurso)
                            <div style="margin-bottom: 8px;">
                                <x-reservas.detalle-recurso 
                                    :nombre="$itemRecurso->aula_nombre ?? ($itemRecurso->act_nombre ?? 'Recurso Seleccionado')" 
                                    :detalle="isset($itemRecurso->aula_capacidad) ? 'Capacidad: ' . $itemRecurso->aula_capacidad : ($itemRecurso->act_serial ?? 'Detalle no disponible')" 
                                    estado="Disponible" 
                                />
                            </div>
                        @endforeach
                    </div>

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
                            <td>{{ $userName }}</td>
                        </tr>
                        <tr>
                            <td>Identificación</td>
                            <td id="resumen-identificacion-preview">{{ $userCedula }}</td>
                        </tr>
                        <tr>
                            <td>Motivo</td>
                            <td class="resaltado-amarillo" id="resumen-motivo-preview" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Por completar</td>
                        </tr>
                        @if($mostrarCampoAula)
                        <tr>
                            <td>Aula de uso</td>
                            <td class="resaltado-amarillo" id="resumen-aula-preview">Por completar</td>
                        </tr>
                        @endif
                    </table>

                    <div class="notificacion-alerta-siger">
                        <p>ℹ️ La reserva pasará al paso de confirmación final.</p>
                    </div>

                    <div class="contenedor-botones">               
                        <button type="submit" class="btn-siger-accion btn" style="width: 100%;">
                            Continuar a Confirmación
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const acordeon = document.querySelector('.acordeon-reserva');

    function verificarResolucion() {
        if (window.innerWidth > 1024 && acordeon) {
            acordeon.setAttribute('open', 'true');
        }
    }
    verificarResolucion();
    window.addEventListener('resize', verificarResolucion);

    const inputFechaInicio = document.getElementById('res_fecha_inicio');
    const inputFechaFin = document.getElementById('res_fecha_fin');
    const inputHoraInicio = document.getElementById('res_hora_inicio');
    const inputHoraFin = document.getElementById('res_hora_fin');

    const previewFecha = document.getElementById('resumen-fecha-preview');
    const previewHorario = document.getElementById('resumen-horario-preview');
    const txtMotivo = document.getElementById('res_motivo');
    const previewMotivo = document.getElementById('resumen-motivo-preview');
    const inputAula = document.getElementById('aula_uso');
    const previewAula = document.getElementById('resumen-aula-preview');

    function actualizarFechaPreview() {
        if (inputFechaInicio && inputFechaFin && previewFecha) {
            const fIni = inputFechaInicio.value;
            const fFin = inputFechaFin.value;
            if (fIni && fFin) {
                previewFecha.textContent = fIni === fFin ? fIni : `${fIni} al ${fFin}`;
                previewFecha.classList.remove('resaltado-amarillo');
            } else if (fIni) {
                previewFecha.textContent = fIni;
                previewFecha.classList.remove('resaltado-amarillo');
            } else {
                previewFecha.textContent = 'Por completar';
                previewFecha.classList.add('resaltado-amarillo');
            }
        }
    }

    function actualizarHorarioPreview() {
        if (inputHoraInicio && inputHoraFin && previewHorario) {
            const hIni = inputHoraInicio.value;
            const hFin = inputHoraFin.value;
            if (hIni && hFin) {
                if (inputFechaInicio && inputFechaFin && inputFechaInicio.value === inputFechaFin.value) {
                    if (hFin <= hIni) {
                        inputHoraFin.setCustomValidity('La hora de fin debe ser posterior a la hora de inicio.');
                    } else {
                        inputHoraFin.setCustomValidity('');
                    }
                } else {
                    inputHoraFin.setCustomValidity('');
                }

                previewHorario.textContent = `${hIni} - ${hFin}`;
                previewHorario.classList.remove('resaltado-amarillo');
            } else {
                previewHorario.textContent = 'Por completar';
                previewHorario.classList.add('resaltado-amarillo');
            }
        }
    }

    if (inputFechaInicio) {
        inputFechaInicio.addEventListener('change', function () {
            if (inputFechaFin) inputFechaFin.min = this.value;
            actualizarFechaPreview();
            actualizarHorarioPreview();
        });
    }

    if (inputFechaFin) {
        inputFechaFin.addEventListener('change', function() {
            actualizarFechaPreview();
            actualizarHorarioPreview();
        });
    }

    if (inputHoraInicio) {
        inputHoraInicio.addEventListener('input', function() {
            if (this.value) {
                inputHoraFin.min = this.value;
            }
            actualizarHorarioPreview();
        });
    }

    if (inputHoraFin) {
        inputHoraFin.addEventListener('input', actualizarHorarioPreview);
    }

    if (txtMotivo && previewMotivo) {
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
        inputAula.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const valor = selectedOption ? selectedOption.text.trim() : '';
            if (this.value !== '') {
                previewAula.textContent = valor;
                previewAula.classList.remove('resaltado-amarillo');
            } else {
                previewAula.textContent = 'Por completar';
                previewAula.classList.add('resaltado-amarillo');
            }
        });
    }

    actualizarFechaPreview();
    actualizarHorarioPreview();
    
    if (txtMotivo && txtMotivo.value.trim() !== '') {
        txtMotivo.dispatchEvent(new Event('input'));
    }
    if (inputAula && inputAula.value !== '') {
        inputAula.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection