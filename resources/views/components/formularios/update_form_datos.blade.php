<form action="#" method="POST" class="grid-campos-perfil">
    @csrf
    
    {{-- Alerta de Notificación --}}
    @if(session('success'))
        <div class="full-width-campo" style="margin-bottom: 1rem;">
            <x-alertas.notificacion tipo="exito">
                {{ session('success') }}
            </x-alertas.notificacion>
        </div>
    @endif

    <div class="post-form">
        <label for="name">Primer Nombre *</label>
        <input type="text" id="name" name="name" required value="{{ Auth::user()->name ?? '' }}">
    </div>

    <div class="post-form">
        <label for="name">Segundo Nombre</label>
        <input type="text" id="name" name="name" required value="{{ Auth::user()->name ?? '' }}">
    </div>

    <div class="post-form">
        <label for="lastname">Primer Apellido *</label>
        <input type="text" id="lastname" name="lastname" required value="{{ Auth::user()->lastname ?? '' }}">
    </div>

    <div class="post-form">
        <label for="lastname">Segundo Apellido</label>
        <input type="text" id="lastname" name="lastname" required value="{{ Auth::user()->lastname ?? '' }}">
    </div>

    <div class="post-form">
        <label for="identificacion">Documento de Identidad *</label>
        <input type="text" id="identificacion" name="identificacion" value="{{ Auth::user()->identificacion ?? '' }}">
    </div>

    <div class="post-form">
        <label for="correo">Correo Electrónico *</label>
        <input type="email" id="correo" name="correo" value="{{ Auth::user()->email ?? '' }}">
    </div>

    {{-- Contenedor de acciones finales --}}
    <div class="botones-accion-final full-width-campo">
        
        {{-- BOTÓN CANCELAR: Usamos 'btn-siger-accion' + 'btn-rojo' (según tu CSS para cancelar/dañado) --}}
        <x-botones.boton id="btn-perfil-cancelar" type="button" class="btn-siger-accion btn-rojo" style="width: auto; padding: 0.75rem 2rem;">
            Cancelar
        </x-botones.boton>

        {{-- BOTÓN GUARDAR: Usamos 'btn-siger-accion' + 'btn-verde-siger' --}}
        <x-botones.boton id="btn-perfil-guardar" type="submit" class="btn-siger-accion btn-verde-siger" style="width: auto; padding: 0.75rem 2rem;">
            Guardar Cambios
        </x-botones.boton>
        
    </div>
</form>