<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon; 
use App\Models\User;
use App\Mail\ResetPasswordMail;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthController extends Controller
{
#Crea usuario
public function create_user(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'role' => 'in:admin,user' // Solo acepta estos roles
    ]);

    $user = new User;
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->role = $request->role ?? 'user'; // Si no envían rol, queda como user
    $user->save();

    return response()->json($user, 201);
}

public function login(Request $request)
{
    $user = User::where('email', $request->email)->first();

    if (! $user || ! \Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    $payload = [
        'sub' => (string) $user->id,
        'email' => $user->email,
        'role' => $user->role, 
        'iat' => Carbon::now()->timestamp,
        'exp' => Carbon::now()->addHours(4)->timestamp
    ];

    $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user_name' => $user->name,
        'role' => $user->role
    ]);
}

#Hace logout y elimina token
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

#RESETEAR CONTRASEÑA CON TOKEN 
#Solicita el token
    public function request_reset_token(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $token = Str::random(6); 

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        #Aqui envia el correo al usuario con el token
          Mail::to($user->email)->send(new ResetPasswordMail($token));

         return response()->json([
          'message' => 'Token de recuperación enviado al correo'
       ]); 
    }

#Aqui resetea la contraseña cuando ya se tiene el token
    public function confirm_reset_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'new_password' => 'required|min:6',
        ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (! $reset) {
            return response()->json(['message' => 'Token inválido o expirado'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->new_password);
        $user->save();

        #Aqui borra el token para que no se reuse
        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Contraseña restablecida correctamente']);
    }

}