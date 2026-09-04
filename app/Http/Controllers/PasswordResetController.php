<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class PasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.recuperar-contrasena'); 
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['correo' => 'required|email|exists:users,USU_CORREO']);

        $user = User::where('USU_CORREO', $request->correo)->first();

        if ($user) {
            // Genera el token nativo y lo guarda automáticamente en password_reset_tokens
            $token = Password::broker()->createToken($user);

            // Dispara la notificación y el envío a la dirección contenida en USU_CORREO
            $user->sendPasswordResetNotification($token);
        }

        return back()->with('success', '¡Se han enviado las instrucciones de recuperación a tu correo electrónico!');
    }

    public function showResetForm(Request $request, $token)
    {
        return view('auth.cambiar-contrasena', [
            'token' => $token, 
            'email' => $request->query('email') // Captura correctamente el correo enviado por GET en la URL
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'correo' => 'required|email|exists:users,USU_CORREO',
            'password' => 'required|min:6|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->correo)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['correo' => 'El token de recuperación es inválido o ha expirado.']);
        }

        $user = User::where('USU_CORREO', $request->correo)->first();
        
        // Actualizamos apuntando a tu columna personalizada
        $user->update([
            'USU_CONTRASEÑA' => Hash::make($request->password)
        ]);

        // Limpiamos el token usado
        DB::table('password_reset_tokens')->where('email', $request->correo)->delete();

        return redirect()->route('login')->with('success', '¡Contraseña actualizada con éxito! Ya puedes iniciar sesión.');
    }
}