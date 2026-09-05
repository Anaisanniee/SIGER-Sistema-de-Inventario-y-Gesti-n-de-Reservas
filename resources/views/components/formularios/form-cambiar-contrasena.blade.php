@props([
    'action' => '#',
    'modo' => 'recuperacion', // 'recuperacion' o 'perfil'
    'token' => '',
    'correo' => '',
    'textoBoton' => 'Actualizar Contraseña',
    'rutaCancelar' => url('/login')
])

<form action="{{ $action }}" method="POST" id="form-cambiar-password" class="text-start">
    @csrf

    @if($modo === 'perfil')
        @method('PUT')
        {{-- Campo de Contraseña Actual solo cuando se edita en el Perfil --}}
        <div class="grupo-formulario full-width-campo mb-3">
            <label for="current_password" class="label-siger">Contraseña Actual *</label>
            <input type="password" id="current_password" name="current_password" required
                   placeholder="Ingresa tu contraseña actual" class="input-siger">
            @error('current_password')
                <small class="text-danger" style="color: red; margin-top: 5px; display: block;">{{ $message }}</small>
            @enderror
        </div>
    @else
        {{-- Campos Ocultos para el token de correo --}}
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="correo" value="{{ $correo }}">
    @endif

    <div class="siger-grid-formulario">
        {{-- Nueva Contraseña --}}
        <div class="grupo-formulario mb-3">
            <label for="new_password" class="label-siger">Nueva Contraseña *</label>
            <input type="password" id="new_password" name="password" required
                   placeholder="Mínimo 6 caracteres" class="input-siger">
            @error('password')
                <small class="text-danger" style="color: red; margin-top: 5px; display: block;">{{ $message }}</small>
            @enderror
        </div>

        {{-- Confirmación de Nueva Contraseña --}}
        <div class="grupo-formulario mb-4">
            <label for="new_password_confirmation" class="label-siger">Confirmar Nueva Contraseña *</label>
            <input type="password" id="new_password_confirmation" name="password_confirmation" required
                   placeholder="Repite tu nueva contraseña" class="input-siger">
        </div>
    </div>

    <div class="siger-form-acciones d-flex gap-2 mt-3">
        <a href="{{ $rutaCancelar }}" class="w-50 text-decoration-none">
            <x-botones.boton type="button" clase="btn-siger-accion btn-cancelar-siger w-100">
                Cancelar
            </x-botones.boton>
        </a>

        <x-botones.boton type="submit" clase="btn-siger-accion btn-verde-siger w-50">
            {{ $textoBoton }}
        </x-botones.boton>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form-cambiar-password');
        const currentPassword = document.getElementById('current_password');
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('new_password_confirmation');

        if (form) {
            form.addEventListener('submit', function(e) {
                if (newPassword.value !== confirmPassword.value) {
                    e.preventDefault();
                    alert('¡Atención! La nueva contraseña y su confirmación no coinciden.');
                    confirmPassword.focus();
                } else if (newPassword.value.length < 6) {
                    e.preventDefault();
                    alert('Por seguridad, la nueva contraseña debe contener un mínimo de 6 caracteres.');
                    newPassword.focus();
                } else if (currentPassword && currentPassword.value === newPassword.value) {
                    e.preventDefault();
                    alert('La nueva contraseña debe ser diferente a la contraseña actual.');
                    newPassword.focus();
                }
            });
        }
    });
</script>