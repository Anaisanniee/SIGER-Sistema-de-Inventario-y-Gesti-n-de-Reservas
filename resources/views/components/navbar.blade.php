{{-- resources/views/components/navbar.blade.php --}}
<link rel="stylesheet" href="{{ asset('css/components/navbarStyle.css') }}">

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="">SIGER</a>

    @if(($mostrarBusqueda ?? true) && View::getSection('mostrarBusqueda') !== 'false')
        <div class="buscador-container" style="position: relative; display: flex; align-items: center;">
            <form action="{{ $rutaBusqueda ?? (Route::has('inventario.index') ? route('inventario.index') : '#') }}" method="GET" style="width: 100%; margin: 0;">
                <input type="text" 
                       name="buscar"
                       id="buscador-recursos" 
                       class="form-control" 
                       placeholder="Buscar..." 
                       value="{{ request('buscar') }}" 
                       onsubmit="return false;"
                       style="padding-left: 15px; padding-right: 40px;">
                
                <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #6c757d; pointer-events: none;"></i>
            </form>
        </div>
    @endif

    <div class="d-flex align-items-center gap-2">
        @if(($mostrarRegresar ?? true) && View::getSection('mostrarRegresar') !== 'false')
            {{-- 
                Determina la ruta: 
                1. $rutaRegresar (variable directa del componente)
                2. @yield('rutaRegresar') (sección definida en la vista blade)
                3. Redirección inteligente al dashboard según el rol del usuario autenticado
            --}}
            @php
                $user = auth()->user();
                $rolNombre = strtolower($user->rol->nombre ?? $user->rol ?? '');
                
                if ($rolNombre === 'docente' || ($user->rol_id ?? null) == 2) {
                    $fallbackRoute = route('dashboard.docente', ['id' => $user->id ?? 1]);
                } elseif ($rolNombre === 'secretaria' || ($user->rol_id ?? null) == 3) {
                    $fallbackRoute = route('dashboard.secretaria');
                } else {
                    $fallbackRoute = route('dashboard.rectora', ['id' => $user->id ?? 1]);
                }

                $urlFinalRegresar = $rutaRegresar ?? (View::hasSection('rutaRegresar') ? View::getSection('rutaRegresar') : $fallbackRoute);
            @endphp

            <a href="{{ $urlFinalRegresar }}" 
               class="btn-back-nav" 
               title="Volver">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endif

        @if(($mostrarPerfil ?? true) && View::getSection('mostrarPerfil') !== 'false')
            <a href="{{ Route::has('perfil') ? route('perfil') : '#' }}" class="nav-link" title="Mi Perfil">
                <i class="fas fa-user"></i>
            </a>
        @endif
    </div>
</nav>