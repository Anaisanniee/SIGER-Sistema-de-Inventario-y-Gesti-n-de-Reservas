<div class="header-modal">

    <div class="header-foto">
    <img src="{{$foto ?? asset('storage/' .$carpeta . '/default-device.png')}}" class="foto-header-img">
    </div>

    <div class="header-info">
        <h2 class="header-nombre-recurso">{{$nombre_recurso}}</h2>
         @if(isset($act_serial))
            <p class="header-codigo">Serial: {{$act_serial}}</p>
         @endif
    </div>

    <div>
            @if(isset($act_estado))   
                <p class="header-estado">Estado: {{$act_estado}}</p>
            @endif
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">X</button>

</div>