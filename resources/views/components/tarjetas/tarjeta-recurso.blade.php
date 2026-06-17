{{-- resources/views/components/tarjetas/tarjeta-recurso.blade.php --}}


<div class="tarjeta-recurso">

    <img
        src="{{ $foto }}"
        alt="Foto del recurso"
        class="tarjeta-img"
    >

    <div class="tarjeta-body">

        <div class="card-title">
            {{ $nombre }}
        </div>

        <div class="estado">
            {{ $etiqueta }}: {{ $valor }}
        </div>

        <div class="botones-container">

            {{-- BOTÓN RESERVAR --}}
            <x-botones.boton
                clase="btn"
                url="{{ $urlBoton ?? '/reservas' }}"
            >{{ $textoBoton ?? 'Reservar' }}</x-botones.boton>

            {{-- BOTÓN FICHA --}}
            <x-botones.boton

                clase="btn"

                target="modalgeneral"



                {{-- CONTROL --}}
                data-tipo="{{ $tipo }}"

                {{-- HEADER --}}
                data-nombre="{{ $nombre }}"

                data-secundario="{{ $valor }}"

                {{-- ACTIVOS --}}
                data-act_id="{{ $recurso->act_id ?? '' }}"

                data-act_marca="{{ $recurso->act_marca ?? 'No registra' }}"

                data-act_estado_fisico="{{ $recurso->act_estado_fisico ?? '' }}"

                data-act_reservable="{{ ($recurso->act_reservable ?? false) ? 'Sí' : 'No' }}"

                data-act_fecha_ingreso="{{ $recurso->act_fecha_ingreso ?? '' }}"

                data-cate_id="{{ $recurso->cate_id ?? '' }}"

                data-aula_nombre="{{ $recurso->aula_nombre ?? 'No asignada' }}"

                data-act_precio_actual="{{ $recurso->act_precio_actual ?? 'No registra' }}"

                {{-- AULAS --}}

                data-aula_id="{{ $recurso->aula_id ?? '' }}"


                data-aula_capacidad="{{ $recurso->aula_capacidad ?? '' }}"

                data-aula_estado="{{ $recurso->aula_estado ?? '' }}"

                data-aula_reservable="{{ ($recurso->aula_reservable ?? false) ? 'Sí' : 'No' }}"

                data-tip_aula_id="{{ $recurso->tip_aula_id ?? '' }}"

            >Ficha técnica</x-botones.boton>
            

        </div>
        
    </div>

</div>