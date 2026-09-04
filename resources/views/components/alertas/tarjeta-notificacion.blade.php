@props(['notificacion', 'descartable' => true])

@php
    $esArray = is_array($notificacion);

    $id = $esArray 
        ? ($notificacion['id'] ?? null) 
        : ($notificacion->id ?? null);

    $tipo = $esArray 
        ? ($notificacion['tipo'] ?? 'info') 
        : ($notificacion->data['tipo'] ?? 'info');

    $iconosDefault = [
        'peligro'     => 'fas fa-exclamation-circle',
        'advertencia' => 'fas fa-tools',
        'exito'       => 'fas fa-check-circle',
        'info'        => 'fas fa-info-circle'
    ];

    $iconoConfigurado = $esArray 
        ? ($notificacion['icono'] ?? null) 
        : ($notificacion->data['icono'] ?? null);

    $iconoClase = $iconoConfigurado 
        ? (str_contains($iconoConfigurado, 'fa-') ? "fas {$iconoConfigurado}" : $iconoConfigurado)
        : ($iconosDefault[$tipo] ?? 'fas fa-bell');

    $esLeida = $esArray 
        ? ($notificacion['leida'] ?? false) 
        : ($notificacion->read_at !== null);

    $titulo = $esArray 
        ? ($notificacion['titulo'] ?? 'Notificación') 
        : ($notificacion->data['titulo'] ?? 'Notificación');

    $mensaje = $esArray 
        ? ($notificacion['mensaje'] ?? 'Sin mensaje') 
        : ($notificacion->data['mensaje'] ?? 'Sin mensaje');

    $fecha = $esArray 
        ? ($notificacion['fecha'] ?? '') 
        : ($notificacion->created_at ? $notificacion->created_at->diffForHumans() : '');

    $claseEstado = $esLeida ? 'noti-leida' : 'noti-no-leida';
@endphp

<div class="noti-card noti-tipo-{{ $tipo }} {{ $claseEstado }} shadow-sm" data-alerta-item>
    

    <div class="d-flex align-items-start gap-3" style="padding-right: 20px;">
        
        <div class="noti-icon-wrapper">
            <i class="{{ $iconoClase }}"></i>
        </div>
        
        <div class="flex-grow-1 noti-contenido">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <h6 class="noti-titulo m-0">{{ $titulo }}</h6>
                
                <div class="d-flex align-items-center gap-2">
                    @if(!$esLeida)
                        <span class="noti-badge-nueva">Nueva</span>
                    @endif
                </div>
            </div>
            
            <p class="noti-mensaje m-0">{{ $mensaje }}</p>
            
            @if($fecha)
                <div class="noti-fecha mt-1">
                    <i class="far fa-clock"></i>
                    <span>{{ $fecha }}</span>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.noti-card {
    position: relative;
    transition: all 0.2s ease-in-out;
    border: 1px solid var(--color-borde);
    border-radius: var(--borde-radio) !important;
    background-color: var(--color-fondo) !important;
    padding: 1rem 1.25rem;
    margin-bottom: 12px;
}
.noti-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06) !important;
}
.noti-contenido {
    padding-right: 10px;
}
.noti-card.noti-no-leida {
    border-left: 4px solid var(--color-principal) !important;
    background-color: #ffffff !important;
}
.noti-card.noti-leida {
    background-color: var(--color-fondo) !important;
    opacity: 0.85;
}
.noti-icon-wrapper {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1.1rem;
    background-color: var(--color-verde-pastel);
    color: var(--principal-secundario);
}
.noti-card.noti-leida .noti-icon-wrapper {
    background-color: var(--color-borde);
    color: var(--color-texto-secundario);
}
.noti-card.noti-tipo-exito.noti-no-leida { border-left-color: var(--color-estado-disponible) !important; }
.noti-card.noti-tipo-exito .noti-icon-wrapper { background-color: var(--color-disponible-pastel); color: var(--principal-secundario); }
.noti-card.noti-tipo-advertencia.noti-no-leida { border-left-color: var(--color-estado-en-mantenimiento) !important; }
.noti-card.noti-tipo-advertencia .noti-icon-wrapper { background-color: var(--color-en-mantenimiento-pastel); color: #d97706; }
.noti-card.noti-tipo-peligro.noti-no-leida { border-left-color: var(--color-estado-dañado) !important; }
.noti-card.noti-tipo-peligro .noti-icon-wrapper { background-color: var(--color-dañado-pastel); color: var(--color-estado-dañado); }
.noti-card.noti-tipo-info.noti-no-leida { border-left-color: var(--color-estado-reservado) !important; }
.noti-card.noti-tipo-info .noti-icon-wrapper { background-color: var(--color-reservado-pastel); color: var(--color-estado-reservado); }
.noti-titulo { font-family: var(--fuente-secundaria); font-size: 0.95rem; font-weight: 600; color: var(--color-texto); }
.noti-mensaje { font-family: var(--fuente-principal); font-size: 0.875rem; color: var(--color-texto-secundario); line-height: 1.4; }
.noti-fecha { font-size: 0.75rem; color: var(--color-azulado); display: flex; align-items: center; gap: 0.3rem; }
.noti-badge-nueva { font-size: 0.7rem; font-weight: 600; padding: 0.25em 0.65em; border-radius: 50rem; background-color: var(--color-principal); color: var(--color-fondo); }
.btn-cerrar-alerta {
    position: absolute;
    top: 10px;
    right: 10px;
    background: transparent !important;
    border: none !important;
    outline: none !important;
    color: var(--color-texto-secundario);
    opacity: 0.5;
    font-size: 0.85rem;
    cursor: pointer;
    padding: 4px 6px;
    border-radius: 50%;
    line-height: 1;
    transition: opacity 0.2s, background-color 0.2s;
    z-index: 5;
}
.btn-cerrar-alerta:hover {
    opacity: 1;
    background-color: var(--color-borde) !important;
}
</style>