<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::all(); // Obtener todos los métodos de pago
        return Inertia::render('PaymentMethods/Index', [
            'paymentMethods' => $paymentMethods
        ]); // Pasar datos a la vista de Vue
    }

    public function edit($id)
    {
        $paymentMethod = PaymentMethod::find($id); // Obtener el método de pago por ID
        return Inertia::render('PaymentMethods/Edit', [
            'paymentMethod' => $paymentMethod
        ]); // Pasar el método de pago a la vista de edición
    }

    public function update(Request $request, $id)
    {
        $paymentMethod = PaymentMethod::find($id);
        $paymentMethod->update($request->all()); // Actualizar con los datos enviados
        return redirect()->route('paymentMethods.index'); // Redirigir al listado después de la actualización
    }
}
