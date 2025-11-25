<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GatewayController extends Controller
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = env('MICROSERVICIO_API_URL');
        $this->apiKey = env('API_KEY'); 
    }

    public function index()
    {
        $url = $this->apiUrl.'/sales';
        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey
        ])->get($url);

        return response()->json();
        }
    
    public function store(Request $request)
    {
        $url = $this->apiUrl . '/sales';
        $data = $request->all();
        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey
        ])->post($url, $data);
        return response()->json();
    }



}