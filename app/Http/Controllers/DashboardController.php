<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Verifica si el usuario está autenticado
        dd(Auth::user());
    
        $pedidos = Pedido::all();
    
        return Inertia::render('Dashboard', [
            'pedidos' => $pedidos,
            'user' => Auth::user(),
        ]);
    }
}
