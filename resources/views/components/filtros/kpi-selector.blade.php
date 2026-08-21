@props([
    'kpis' => [] // Recibiremos un arreglo con la configuración de cada tarjeta KPI
])

<link rel="stylesheet" href="{{ asset('css/pages/recursos-index.css') }}">
<div class="metricas-resumen-grid">
    @foreach($kpis as $index => $kpi)
        <div class="tarjeta-metrica-kpi kpi-filtro {{ $index === 0 ? 'active' : '' }}" 
             data-filtro="{{ $kpi['filtro'] }}" 
             style="cursor: pointer;">
            
            <div class="icono-kpi {{ $kpi['color'] }}">
                <i class="{{ $kpi['icono'] }}"></i>
            </div>
            
            <div class="datos-kpi">
                <span class="cifra-kpi">{{ $kpi['titulo'] }}</span>
                <span class="label-kpi">{{ $kpi['subtitulo'] }}</span>
            </div>
        </div>
    @endforeach
</div>