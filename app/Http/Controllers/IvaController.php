<?php

namespace App\Http\Controllers;

use App\Models\Iva;
use Illuminate\Http\Request;

class IvaController extends Controller
{
    public function updateRate(Request $request)
    {
        $request->validate([
            'iva_rate' => 'required|numeric|min:0|max:100',
        ]);

        $iva = Iva::latest()->first();

        if ($iva) {
            $iva->update([
                'iva_rate' => $request->iva_rate, // Stored as percentage (e.g. 16)
            ]);
        } else {
            Iva::create([
                'iva_rate' => $request->iva_rate, // Stored as percentage (e.g. 16)
            ]);
        }

        return back()->with('success', 'IVA rate updated successfully.');
    }

    public function getRate()
    {
        $iva = Iva::latest()->first();

        return response()->json([
            'rate' => $iva ? $iva->iva_rate : 16, // Default 16%
        ]);
    }
}
