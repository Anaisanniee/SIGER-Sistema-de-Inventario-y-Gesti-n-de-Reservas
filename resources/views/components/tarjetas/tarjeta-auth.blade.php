@props([
    'icono' => 'fas fa-shield-alt',
    'titulo' => 'SIGER',
    'subtitulo' => ''
])

<div class="siger-auth-container d-flex justify-content-center align-items-center min-vh-100">
    <div class="siger-auth-card p-4 rounded-4 shadow-sm bg-white" style="max-width: 450px; width: 100%;">
        <div class="text-center mb-4">
            <div class="siger-auth-icon-wrapper mb-3">
                <i class="{{ $icono }} fa-2x text-success"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">{{ $titulo }}</h3>
            @if($subtitulo)
                <p class="text-muted small mb-0">{{ $subtitulo }}</p>
            @endif
        </div>

        <div class="siger-auth-body">
            {{ $slot }}
        </div>
    </div>
</div>