<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GateController extends Controller
{
    public function handle(Request $request, $service, $path = '')
    {
        $services = config('services.ms');

        if (!isset($services[$service])) {
            return response()->json(['error' => 'Servicio no encontrado'], 404);
        }

        $url = rtrim($services[$service], '/') . '/' . ltrim($path, '/');

        try {
            $options = [];

            if ($request->isMethod('get')) {
                // Para GET enviar query params
                $options['query'] = $request->query();
            } else {
                // Para POST, PUT, PATCH enviar body tal cual
                $options['body'] = $request->getContent();
                $options['headers']['Content-Type'] = $request->header('Content-Type', 'application/json');
            }

            $response = Http::withHeaders($request->headers->all())
                            ->send($request->method(), $url, $options);

            // Limpiar headers innecesarios
            $skip = ['transfer-encoding','content-length','content-encoding','connection','server','date'];
            $cleanHeaders = [];
            foreach ($response->headers() as $k => $v) {
                if (!in_array(strtolower($k), $skip)) {
                    $cleanHeaders[$k] = $v;
                }
            }

            return response($response->body(), $response->status())
                   ->withHeaders($cleanHeaders);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Microservicio no disponible',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
