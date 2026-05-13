<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class EventDemoController extends Controller
{
    /**
     * Muestra la página de demostración de eventos de Inertia.
     * 
     * Esta página sirve como ejemplo educativo para entender el sistema
     * de eventos de Inertia.js en el lado del cliente.
     */
    public function index()
    {
        return inertia('event-demo/Index');
    }

    /**
     * Endpoint de ejemplo para demostrar eventos de navegación.
     * Simula una carga lenta para que puedas ver los eventos en acción.
     */
    public function slow()
    {
        sleep(2);
        
        return inertia('event-demo/Index', [
            'message' => '¡Página cargada después de 2 segundos de delay!',
            'loadedAt' => now()->toIso8601String(),
        ]);
    }

    /**
     * Endpoint que devuelve un error para demostrar el evento 'exception'.
     */
    public function error()
    {
        abort(500, 'Este es un error de demostración para el evento exception');
    }

    /**
     * Endpoint de ejemplo con carga de archivos para demostrar el evento 'progress'.
     */
    public function upload()
    {
        // Simula procesamiento de archivo
        sleep(1);
        
        return response()->json([
            'success' => true,
            'message' => 'Archivo procesado correctamente',
        ]);
    }
}