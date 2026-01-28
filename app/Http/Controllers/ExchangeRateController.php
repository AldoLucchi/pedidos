<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function updateRate(Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric|min:0',
        ]);

        // Guardar o actualizar la tasa (asumo que solo hay una tasa vigente)
        $rate = ExchangeRate::latest()->first();
        if ($rate) {
            $rate->update(['rate' => $request->rate]);
        } else {
            ExchangeRate::create(['rate' => $request->rate]);
        }

        return back()->with('success', 'Tasa de cambio actualizada.');
    }

    public function getRate()
    {
        // Obtener el último registro (el que tiene el ID más alto)
        $rate = ExchangeRate::latest()->first();
    
        return response()->json([
            'rate' => $rate ? $rate->rate : 1, // Si no hay tasa, usa 1
        ]);
    }
}
