<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NuevoPedidoMail;

class MailController extends Controller
{
    public function recibir(Request $request)
    {
        \Log::info('📩 Webhook recibido:', $request->all());

        $tipo = $request->input('tipo_evento');
        $datos = $request->input('datos');

        if ($tipo === 'nuevo_pedido') {
            $email = $datos['email'] ?? null;

            if ($email) {
                Mail::to($email)->send(new NuevoPedidoMail($datos));
                return response()->json(['message' => 'Correo enviado correctamente']);
            } else {
                return response()->json(['error' => 'No se proporcionó email'], 400);
            }
        }

        return response()->json(['error' => 'Evento no reconocido'], 400);
    }

}
