@extends('layouts.app')
@section('content')
    <h1>Pruebas</h1>
    <p>Esta es una página de pruebas para verificar la integración de los componentes de fichas técnicas.</p>

    <div class="fichas-container">
        @include('componentes.fichas.ficha-tecnica-aula')
        @include('componentes.fichas.ficha-tecnica-activo')
    </div>
@endsection