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


        @if(($mostrarPerfil ?? true) && View::getSection('mostrarPerfil') !== 'false')
            @php
                $userAuth = auth()->user();
                
                // Obtención de slug, nombre e id según la tabla roles (id 1: Secretaria, id 2: Rectora, id 3: Docente)
                $slugRol = strtolower($userAuth->rol->slug ?? $userAuth->role->slug ?? '');
                $nombreRol = strtolower($userAuth->rol->name ?? $userAuth->rol->nombre ?? $userAuth->role->name ?? '');
                $rolIdUser = $userAuth->rol_id ?? $userAuth->role_id ?? null;

                // Validación exacta de roles
                $esSecretaria = ($slugRol === 'secretaria' || $nombreRol === 'secretaria' || $rolIdUser == 1);
                $esRectora    = ($slugRol === 'rectora' || $nombreRol === 'rectora' || $rolIdUser == 2);
                
                $puedeVerInformes = ($esSecretaria || $esRectora);
            @endphp

            <div class="perfil-dropdown-container">
                <button type="button" class="perfil-dropdown-btn" id="btnPerfilDropdown" onclick="toggleMenuPerfil(event)" title="Menú de opciones">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="perfil-dropdown-menu" id="menuPerfilDropdown">

                   {{--SECCION 1  PARA TODOS--}}

                    {{--si estas en otra pagiandifernet ala dasboard se oculta se muestra el bton de ir al inicio dependiento el rol--}}
                    @if (!request()->routeIs('dashboard.*'))
                        @php
                            $userDashboard = auth()->user();
                            $slugDashboard = strtolower($userDashboard->rol->slug ?? $userDashboard->role->slug ?? '');
                            $idDashboard = $userDashboard->rol_id ?? $userDashboard->role_id ?? null;

                            if ($slugDashboard === 'secretaria' || $idDashboard == 1) {
                                $rutaHome = route('dashboard.secretaria');
                            } elseif ($slugDashboard === 'rectora' || $idDashboard == 2) {
                                $rutaHome = route('dashboard.rectora', ['id' => $userDashboard->id ?? 2]);
                            } else {
                                $rutaHome = route('dashboard.docente', ['id' => $userDashboard->id ?? 3]);
                            }
                        @endphp

                        <a href="{{ $rutaHome }}" class="dropdown-item">
                            <i class="fas fa-home"></i> Ir al Inicio
                        </a>
                    @endif


                     {{--si se está en la página de perfil se oculta el --}}
                    @if (!request()->routeIs('perfil'))
                        <a href="{{ Route::has('perfil') ? route('perfil') : '#' }}" class="dropdown-item">
                            <i class="fas fa-user-circle"></i> Mi Perfil
                        </a>
                    @endif

                    @php
                        $notificacionespage = Route::has('notificaciones') ? route('notificaciones') : '#';
                    @endphp
                    <a href="{{$notificacionespage}}" class="dropdown-item">
                        <i class="fas fa-bell"></i> Mis Notificaciones
                    </a>

                    {{--SECCION 2 Visibles únicamente para Secretaría y Rectora --}}
                    @if($puedeVerInformes)  
                        <div class="dropdown-divider"></div>

                        
                        @php
                            $routeReservas = Route::has('secretaria.informe') ? route('secretaria.informe') : (Route::has('secretaria.informe') ? route('secretaria.informe') : null);
                            $routeInventario = Route::has('secretaria.informe') ? route('secretaria.informe') : (Route::has('secretaria.informe') ? route('secretaria.informe') : null);
                        @endphp

                        @if($routeReservas)
                            <a href="{{ $routeReservas }}" class="dropdown-item">
                                <i class="fas fa-file-alt"></i> Informe de Reservas
                            </a>
                        @endif

                        @if($routeInventario)
                            <a href="{{ $routeInventario }}" class="dropdown-item">
                                <i class="fas fa-boxes"></i> Informes de Inventario
                            </a>
                        @endif
                    @endif

                    {{-- SECCION 3: Exclusivo para Secretaría --}}
                    @php
                        $routeUsuarios = Route::has('usuarios.index') ? route('usuarios.index') : (Route::has('users.index') ? route('users.index') : null);
                    @endphp

                    @if($esSecretaria && $routeUsuarios)
                        <a href="{{ $routeUsuarios }}" class="dropdown-item">
                            <i class="fas fa-users-cog"></i> Gestionar Usuarios
                        </a>
                    @endif

                    <div class="dropdown-divider"></div>

                    {{-- Opción para cerrar sesión --}}
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <x-botones.boton type="submit" class="dropdown-item btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </x-botones.boton>
                    </form>
                </div>
            </div>
        @endif
    </div>
</nav>

<script>
    function toggleMenuPerfil(event) {
        event.stopPropagation();
        const menu = document.getElementById('menuPerfilDropdown');
        if (menu) {
            menu.classList.toggle('mostrar');
        }
    }

    window.addEventListener('click', function(event) {
        const menu = document.getElementById('menuPerfilDropdown');
        const btn = document.getElementById('btnPerfilDropdown');
        
        if (menu && menu.classList.contains('mostrar')) {
            if (!menu.contains(event.target) && !btn.contains(event.target)) {
                menu.classList.remove('mostrar');
            }
        }
    });
</script>