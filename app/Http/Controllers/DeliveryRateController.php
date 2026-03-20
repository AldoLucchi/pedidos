<?php

namespace App\Http\Controllers;

use App\Models\DeliveryRate;
use Illuminate\Http\Request;

class DeliveryRateController extends Controller
{
    public function updateRate(Request $request)
    {
        $request->validate([
            'delivery_rate' => 'required|numeric|min:0|max:100', // Validar 'delivery_rate'
        ]);

        $rate = DeliveryRate::latest()->first();
        if ($rate) {
            $rate->update(['delivery_rate' => $request->delivery_rate]); // Usar 'delivery_rate'
        } else {
            DeliveryRate::create(['delivery_rate' => $request->delivery_rate]); // Usar 'delivery_rate'
        }

        return back()->with('success', 'Porcentaje de delivery actualizado.');
    }

    public function getRate()
    {
        $rate = DeliveryRate::latest()->first();

        return response()->json([
            'rate' => $rate ? $rate->delivery_rate : 16, // Usar 'delivery_rate'
        ]);
    }
}