@props([
    'icono' => 'fas fa-shield-alt',
    'titulo' => 'SIGER',
    'subtitulo' => ''
])

<div class="siger-auth-container d-flex justify-content-center align-items-center py-4">
    <div class="siger-auth-card p-4 rounded-4 shadow-sm bg-white" style="max-width: 550px; width: 100%;">
        
        <div class="auth-card-header text-center mb-4">
            <i class="{{ $icono }} fa-2x mb-2" style="color: var(--color-principal);"></i>
            <h3 class="titulo-siger">{{ $titulo }}</h3>
            @if($subtitulo)
                <p class="subtitulo-siger text-muted">{{ $subtitulo }}</p>
            @endif
        </div>

        <div class="siger-auth-body">
            {{ $slot }}
        </div>

    </div>
</div>