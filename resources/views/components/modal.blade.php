{{-- resources/views/components/modal.blade.php --}}
@props([
    'id' => 'modalgeneral',
    'titulo' => 'Nombre',
    'subtitulo' => 'Característica'
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-content-siger">
            
            <div class="modal-header">
                <h4 class="modal-title" id="modal-titulo-dinamico">{{ $titulo }}</h4>
                <h6 class="modal-subtitle" id="modal-sub-dinamico">{{ $subtitulo }}</h6>
                <button type="button" class="btn-close-x" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            
            <div class="modal-body">
                {{ $slot }}
            </div>
            
            <div class="modal-footer">
                <x-botones.boton type="button" clase="btn btn-perfil-guardar" data-bs-dismiss="modal">
                    Cerrar
                </x-botones.boton>
            </div>
            
        </div>
    </div>
</div>