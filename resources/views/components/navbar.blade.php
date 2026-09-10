{{-- resources/views/components/navbar.blade.php --}}
<link rel="stylesheet" href="{{ asset('css/components/navbarStyle.css') }}">

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <img src="{{ asset('storage/images/logo-siger.png') }}" alt="Logo" class="navbar-logo">

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
            @php
                $rutaEspecificada = $rutaRegresar ?? (View::hasSection('rutaRegresar') ? View::getSection('rutaRegresar') : null);

                if ($rutaEspecificada) {
                    $urlFinalRegresar = $rutaEspecificada;
                } else {
                    $user = auth()->user();
                    $slugRolNav = strtolower($user->rol->slug ?? $user->role->slug ?? '');
                    $nombreRolNav = strtolower($user->rol->name ?? $user->rol->nombre ?? $user->role->name ?? '');
                    $rolIdNav = $user->rol_id ?? $user->role_id ?? null;
                    
                    if ($slugRolNav === 'docente' || $nombreRolNav === 'docente' || $rolIdNav == 3) {
                        $urlFinalRegresar = route('dashboard.docente', ['id' => $user->id]);
                    } elseif ($slugRolNav === 'secretaria' || $nombreRolNav === 'secretaria' || $rolIdNav == 1) {
                        $urlFinalRegresar = route('dashboard.secretaria');
                    } else {
                        $urlFinalRegresar = route('dashboard.rectora', ['id' => $user->id]);
                    }
                }
            @endphp

            <a href="{{ $urlFinalRegresar }}" 
            class="btn-back-nav" 
            title="Volver">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endif

        @if(($mostrarPerfil ?? true) && View::getSection('mostrarPerfil') !== 'false')
            @php
                $userAuth = auth()->user();
                
                $slugRol = strtolower($userAuth->rol->slug ?? $userAuth->role->slug ?? '');
                $nombreRol = strtolower($userAuth->rol->name ?? $userAuth->rol->nombre ?? $userAuth->role->name ?? '');
                $rolIdUser = $userAuth->rol_id ?? $userAuth->role_id ?? null;

                $esSecretaria = ($slugRol === 'secretaria' || $nombreRol === 'secretaria' || $rolIdUser == 1);
                $esRectora    = ($slugRol === 'rectora' || $nombreRol === 'rectora' || $rolIdUser == 2);
                
                $puedeVerInformes = ($esSecretaria || $esRectora);

                // 🍪 LECTURA DE LA COOKIE (Sobrevive al cierre de sesión y al navegador)
                $cookieValor = request()->cookie('last_seen_notifications');
                $ultimaVista = $cookieValor 
                    ? \Carbon\Carbon::parse($cookieValor) 
                    : \Carbon\Carbon::now()->subDays(3);

                $hasNewNotifications = false;

                if ($userAuth) {
                    $userIdNav = $userAuth->usu_id ?? $userAuth->id;
                    
                    if ($esSecretaria) {
                        $ahora = \Carbon\Carbon::now();
                        $hoy = \Carbon\Carbon::today();

                        $hasNewNotifications = \App\Models\ReservasModels::whereIn('res_estado_reserva', ['Aprobada', 'aprobada'])
                            ->where('updated_at', '>', $ultimaVista)
                            ->whereHas('detalles', function($q) use ($hoy, $ahora) {
                                $q->whereDate('det_re_fecha_fin', $hoy)
                                  ->where('det_re_fecha_fin', '>', $ahora);
                            })->exists();
                    } else {
                        $hasNewNotifications = \App\Models\ReservasModels::where('usu_id', $userIdNav)
                            ->whereIn('res_estado_reserva', ['Aprobada', 'Rechazada'])
                            ->where('updated_at', '>', $ultimaVista)
                            ->exists();
                    }
                }
            @endphp

            <div class="perfil-dropdown-container">
                {{-- 🔴 BOTÓN DE LAS 3 RAYAS CON EL PUNTICO ROJO CONDICIONAL --}}
                <button type="button" class="perfil-dropdown-btn" id="btnPerfilDropdown" onclick="toggleMenuPerfil(event)" title="Menú de opciones" style="position: relative;">
                    <i class="fas fa-bars"></i>
                    @if($hasNewNotifications)
                        <span class="rounded-circle" style="position: absolute; top: 4px; right: 4px; width: 9px; height: 9px; background-color: #dc3545 !important; display: inline-block;"></span>
                    @endif
                </button>

                <div class="perfil-dropdown-menu" id="menuPerfilDropdown">

                   {{--SECCION 1  PARA TODOS--}}
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

                    @if(!request()->routeIs('perfil'))
                        <a href="{{ route('perfil') }}" class="dropdown-item">
                            <i class="fas fa-user"></i> Mi Perfil
                        </a>
                    @endif

                    {{-- 🔴 OPCIÓN DE NOTIFICACIONES EN EL MENÚ DESPLEGABLE --}}
                    <a href="{{ route('notificaciones') }}" class="dropdown-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-bell"></i> Mis Notificaciones</span>
                        @if($hasNewNotifications)
                            <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #dc3545 !important; display: inline-block;"></span>
                        @endif
                    </a>

                    {{--SECCION 1.5: MIS RESERVAS PARA RECTOR Y DOCENTE--}}
                    @if($slugRol === 'docente' || $nombreRol === 'docente' || $rolIdUser == 3 || $slugRol === 'rectora' || $nombreRol === 'rectora' || $rolIdUser == 2)
                        <a href="{{ route('mis.reservas') }}" class="dropdown-item">
                            <i class="fas fa-calendar-check"></i> Mis Reservas
                        </a>
                    @endif

                    {{--SECCION 2 Visibles únicamente para Secretaría y Rectora --}}
                    @if($puedeVerInformes)  
                        <div class="dropdown-divider"></div>
                        
                        @php
                            $routeReservas = Route::has('secretaria.informe') ? route('secretaria.informe') : null;
                            $routeInventario = Route::has('informes.inventario') ? route('informes.inventario') : null;
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