@extends('layouts.app')
@section('mostrarPerfil', 'false')
@section('mostrarBusqueda', 'false')
@section('content')
@php
<<<<<<< HEAD
    // La variable no viene del controlador esta en rutas de prueba cambiar cuando se pase a controlador
    // Filtra los datos de la reserva según el tipo de recurso (activo o aula) para mostrar u ocultar el campo "Aula de uso" en la vista de reserva.
    // $recursos = session('recursos', []); 
    // $recurso = isset($recursos[0]) ? $recursos[0] : null; 
    $tipoRecurso = isset($recurso) && is_object($recurso) ? $recurso->tipo : 'activo';
@endphp
@php
    // Ejemplo de array de recursos (múltiples equipos para pruebas)
    $recursos = $recursos ?? [
        (object)[
            'tipo' => 'activo',
            'nombre' => 'Computador Portátil Dell Inspiron 15',
            'serial' => 'DELL-5420-X92',
            'marca' => 'Dell'
        ],
        (object)[
            'tipo' => 'activo',
            'nombre' => 'Video VideoProyector Epson PowerLite',
            'serial' => 'EPS-880-VP9',
            'marca' => 'Epson'
        ],
        (object)[
            'tipo' => 'activo',
            'nombre' => 'Sistema de Sonido / Cabina Cabina Bluetooth 8" ',
            'serial' => 'JBL-PARTY-04',
            'marca' => 'JBL'
        ],
        (object)[
            'tipo' => 'activo',
            'nombre' => 'Tableta de Dibujo Wacom Intuos',
            'serial' => 'WAC-CTL4100-88',
            'marca' => 'Wacom'
        ],
        (object)[
            'tipo' => 'activo',
            'nombre' => 'Camára Réflex Digital Canon EOS Rebel',
            'serial' => 'CAN-T7-4921',
            'marca' => 'Canon'
        ]
    ];
=======
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
>>>>>>> origin/backend-Elias
@endphp
<link rel="stylesheet" href="{{ asset('css/components/stepper.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/detalle-recurso.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/reservas.css') }}">

<div class="contenedor-reserva-universal">
    
    {{-- 1. COMPONENTE STEPPER (Barra de Progreso en Paso 2) --}}
    <x-reservas.stepper paso="2" />

<<<<<<< HEAD
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
                    :tipoRecurso="$recursos[0]->tipo ?? 'activo'"
                    :recursoNombre="$recursos[0]->nombre ?? $recursos[0]->nombres ?? 'Recurso'"
                    :serial="$recursos[0]->serial ?? 'Sin Serial'"
                    :marca="$recursos[0]->marca ?? 'N/A'"
                    :recursos="$recursos" 
                />
            </div>

            {{-- Formulario Dinámico de Reserva --}}
            <form action="#" method="POST" class="formulario-dinamico">
                @csrf
                
=======
    {{-- 2. FORMULARIO GLOBAL QUE ENVÍA TODO AL CONTROLADOR (Paso 2 -> Paso 3) --}}
    <form action="{{ route('reservas.paso2.post') }}" method="POST" class="formulario-dinamico" id="form-reserva-paso2">
        @csrf
        
        {{-- REJILLA DE DISTRIBUCIÓN PRINCIPAL (Grid) --}}
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
                    @php
                        $primerRecurso = $recursosColeccion->first();
                    @endphp
                    <x-reservas.detalle-recurso 
                        :tipoRecurso="$tipoSession ?? 'activo'"
                        :recursoNombre="$primerRecurso->act_nombre ?? ($primerRecurso->aula_nombre ?? 'Recurso')"
                        :serial="$primerRecurso->act_serial ?? 'Sin Serial'"
                        :marca="$primerRecurso->marca ?? 'N/A'"
                        :recursos="$recursosColeccion" 
                    />
                </div>

>>>>>>> origin/backend-Elias
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
<<<<<<< HEAD
                                    value="{{ old('res_fecha_inicio') }}">
=======
                                    min="{{ date('Y-m-d') }}"
                                    value="{{ old('res_fecha_inicio', $valFechaInicio) }}">
>>>>>>> origin/backend-Elias
                            </div>

                            <div class="post-form">
                                <label for="res_fecha_fin">Fecha de Fin <span class="text-danger">*</span></label>
                                <input type="date" id="res_fecha_fin" name="res_fecha_fin" required 
<<<<<<< HEAD
                                    value="{{ old('res_fecha_fin') }}">
=======
                                    min="{{ date('Y-m-d') }}"
                                    value="{{ old('res_fecha_fin', $valFechaFin) }}">
>>>>>>> origin/backend-Elias
                            </div>
                        </div>

                        {{-- Rango de Horas Manuales Pareado --}}
                        <div class="grid-dos-columnas margin-top-main">
                            <div class="post-form">
                                <label for="res_hora_inicio">Hora de Inicio <span class="text-danger">*</span></label>
                                <input type="time" id="res_hora_inicio" name="res_hora_inicio" required 
<<<<<<< HEAD
                                    value="{{ old('res_hora_inicio') }}">
=======
                                    value="{{ old('res_hora_inicio', $valHoraInicio) }}">
>>>>>>> origin/backend-Elias
                            </div>

                            <div class="post-form">
                                <label for="res_hora_fin">Hora de Fin <span class="text-danger">*</span></label>
                                <input type="time" id="res_hora_fin" name="res_hora_fin" required 
<<<<<<< HEAD
                                    value="{{ old('res_hora_fin') }}">
=======
                                    value="{{ old('res_hora_fin', $valHoraFin) }}">
>>>>>>> origin/backend-Elias
                            </div>
                        </div>
                        
                        {{-- Lógica Inteligente de Columnas para Motivo y Aula --}}
<<<<<<< HEAD
                        @if($tipoRecurso !== 'aula')
                            {{-- CASO A: El recurso NO es un aula. Renderiza ambos en DOS columnas --}}
=======
                        @if($mostrarCampoAula)
>>>>>>> origin/backend-Elias
                            <div class="grid-dos-columnas margin-top-main">
                                <div class="post-form">
                                    <label for="res_motivo" class="form-label-siger">Motivo o Justificación de la Reserva <span class="text-danger">*</span></label>
                                    <textarea 
                                        id="res_motivo" 
                                        name="res_motivo" 
                                        class="form-control" 
                                        rows="3" 
                                        style="height: auto !important; min-height: 90px; border-radius: 8px !important; padding: 10px 15px !important; resize: none; outline: none !important; box-shadow: none !important; border: 1px solid var(--color-fondo, #ccc);" 
<<<<<<< HEAD
                                        onfocus="this.style.border='1px solid var(--color-principal, #28a745)'"
                                        onblur="this.style.border='1px solid var(--color-fondo, #ccc)'"
                                        required 
                                        placeholder="Ej. Práctica de laboratorio con estudiantes de décimo..."
                                    >{{ old('res_motivo') }}</textarea>
=======
                                        required 
                                        placeholder="Ej. Práctica de laboratorio con estudiantes..."
                                    >{{ old('res_motivo', session('reserva.res_motivo')) }}</textarea>
>>>>>>> origin/backend-Elias
                                </div>

                                <div class="post-form">
                                    <label for="aula_uso">¿En qué aula o salón utilizará el recurso? <span class="text-danger">*</span></label>
<<<<<<< HEAD
                                    <input type="text" 
                                        name="aula_uso" 
                                        id="aula_uso" 
                                        list="lista-salones" 
                                        placeholder="Escribe para buscar o seleccionar el salón..." 
                                        required 
                                        class="input-siger"
                                        value="{{ old('aula_uso') }}"
                                        autocomplete="off">

                                    <datalist id="lista-salones">
                                        <option value="Salón 601"></option>
                                        <option value="Salón 702"></option>
                                        <option value="Laboratorio de Ciencias"></option>
                                        <option value="Sala de Sistemas A"></option>
                                        <option value="Sala de Sistemas B"></option>
                                        <option value="Biblioteca Principal"></option>
                                    </datalist>
                                </div>
                            </div>
                        @else
                            {{-- CASO B: El recurso SÍ es un aula. Renderiza SOLO el motivo a ANCHO COMPLETO --}}
=======
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
>>>>>>> origin/backend-Elias
                            <div class="margin-top-main">
                                <div class="post-form">
                                    <label for="res_motivo" class="form-label-siger">Motivo o Justificación de la Reserva <span class="text-danger">*</span></label>
                                    <textarea 
                                        id="res_motivo" 
                                        name="res_motivo" 
                                        class="form-control" 
                                        rows="3" 
                                        style="height: auto !important; min-height: 90px; border-radius: 8px !important; padding: 10px 15px !important; resize: none; outline: none !important; box-shadow: none !important; border: 1px solid var(--color-fondo, #ccc);" 
<<<<<<< HEAD
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

                {{-- Tabla de Detalles Requeridos --}}
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
                        <td class="resaltado-amarillo" id="resumen-identificacion-preview">Por completar</td>
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

        // Escucha la escritura o selección en el input del aula de destino
        if (inputAula && previewAula) {
            inputAula.addEventListener('input', function() {
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
=======
                                        required 
                                        placeholder="Ej. Práctica de laboratorio con estudiantes..."
                                    >{{ old('res_motivo', session('reserva.res_motivo')) }}</textarea>
                                </div>
                            </div>
                            {{-- Input oculto que envía automáticamente el ID del aula en reservas mixtas --}}
                            <input type="hidden" name="aula_uso" value="{{ old('aula_uso', $aulaAutomaticaId) }}">
                        @endif

                        {{-- Mensaje de validación de backend (si existe) --}}
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

                    {{-- Tabla de Detalles Requeridos --}}
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

                    {{-- Alerta Informativa sobre la validación del backend --}}
                    <div class="notificacion-alerta-siger">
                        <p>ℹ️ La reserva quedará pendiente de aprobación por el administrador.</p>
                    </div>

                    {{-- Botón de envío modificado a type="submit" para que procese el formulario y avance al paso 3 --}}
                    <div class="contenedor-botones">            
                        <button type="submit" class="btn-siger-accion btn" style="width: 100%;">
                            Confirmar Reserva
                        </button>
                    </div>
                </div>
            </div> {{-- Fin Columna Derecha --}}

        </div> {{-- Fin del Grid --}}
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
>>>>>>> origin/backend-Elias
