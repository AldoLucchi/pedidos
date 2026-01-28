<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\ExchangeRate;
use App\Models\DeliveryRate;
use App\Models\Iva;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PedidoController extends Controller
{
    /**
     * Display the dashboard with the list of orders
     */
    public function index()
    {
        $deliveryRateModel = DeliveryRate::latest()->first();
        $deliveryRate = $deliveryRateModel ? $deliveryRateModel->delivery_rate : 16;

        $exchangeRateModel = ExchangeRate::latest()->first();
        $exchangeRate = $exchangeRateModel ? $exchangeRateModel->rate : 1;

        $ivaModel = Iva::latest()->first();
        $ivaRate = $ivaModel ? $ivaModel->iva_rate : 16; // Stored as percentage
        $ivaDecimal = $ivaRate / 100; // Convert to decimal for calculations

        $pedidos = Pedido::with('productos')
            ->orderBy('fecha_pedido', 'desc')
            ->paginate(10);

        return Inertia::render('Dashboard', [
            'pedidos'      => $pedidos,
            'user'         => Auth::user(),
            'exchangeRate' => $exchangeRate,
            'deliveryRate' => $deliveryRate,
            'ivaRate'      => $ivaRate,
        ]);
    }

    /**
     * Show the order creation form
     */
    public function create()
    {
        $deliveryRateModel = DeliveryRate::latest()->first();
        $deliveryRate = $deliveryRateModel ? $deliveryRateModel->delivery_rate : 1;

        $exchangeRateModel = ExchangeRate::latest()->first();
        $exchangeRate = $exchangeRateModel ? $exchangeRateModel->rate : 1;

        $ivaModel = Iva::latest()->first();
        $ivaRate = $ivaModel ? $ivaModel->iva_rate : 16; // Percentage
        $ivaDecimal = $ivaRate / 100;

        $productos = Producto::all();

        return Inertia::render('CreatePedido', [
            'productos'    => $productos,
            'deliveryRate' => $deliveryRate,
            'exchangeRate' => $exchangeRate,
            'ivaRate'      => $ivaRate,
        ]);
    }

    /**
     * Store a new order in the database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_nombre'           => 'nullable|string|max:255',
            'cliente_telefono'         => 'nullable|string|max:20',
            'cliente_direccion'        => 'nullable|string',
            'metodo_pago'              => 'nullable|string',
            'fecha_pedido'             => 'nullable|date',
            'notas'                    => 'nullable|string',
            'random_value_1'           => 'nullable|string',
            'random_value_2'           => 'nullable|url',
            'random_value_3'           => 'nullable|string',
            'productos'                => 'nullable|array|min:1',
            'productos.*.producto_id'  => 'nullable|exists:productos,id',
            'productos.*.cantidad'     => 'nullable|integer|min:1',
        ]);

        // Get latest IVA and convert to decimal
        $ivaModel = Iva::latest()->first();
        $ivaRate = $ivaModel ? $ivaModel->iva_rate : 16; // Percentage
        $ivaDecimal = $ivaRate / 100;

        DB::transaction(function () use ($validated, $ivaDecimal, $ivaRate) {

            $pedido = Pedido::create([
                'cliente_nombre'    => $validated['cliente_nombre'] ?? null,
                'cliente_telefono'  => $validated['cliente_telefono'] ?? null,
                'cliente_direccion' => $validated['cliente_direccion'] ?? null,
                'metodo_pago'       => $validated['metodo_pago'] ?? null,
                'fecha_pedido'      => $validated['fecha_pedido'] ?? now(),
                'notas'             => $validated['notas'] ?? null,
                'random_value_1'    => $validated['random_value_1'] ?? null,
                'random_value_2'    => $validated['random_value_2'] ?? null,
                'random_value_3'    => $validated['random_value_3'] ?? null,
                'total'             => 0,
            ]);

            if (!empty($validated['productos'])) {
                foreach ($validated['productos'] as $producto) {
                    $productoModel = Producto::find($producto['producto_id']);

                    if ($productoModel) {
                        $pedido->productos()->attach(
                            $producto['producto_id'],
                            [
                                'cantidad'        => $producto['cantidad'],
                                'precio_unitario' => $productoModel->precio,
                            ]
                        );
                    }
                }
            }

            $pedido->load('productos');

            // Calculate subtotal
            $subtotal = $pedido->productos->sum(function ($producto) {
                return $producto->pivot->cantidad * $producto->pivot->precio_unitario;
            });

            // Calculate IVA amount
            $ivaAmount = $subtotal * $ivaDecimal;

            // Calculate total including IVA
            $total = $subtotal + $ivaAmount;

            $pedido->update([
                'total'      => $total,
                'iva_rate'   => $ivaRate,  // Store percentage for record
                'iva_amount' => $ivaAmount,
            ]);
        });

        return back()->with('success', 'Pedido created successfully');
    }

    /**
     * Check if there are new incoming orders
     */
    public function checkNewPedidos(Request $request)
    {
        $lastCheck = $request->input('since');

        if (!$lastCheck || !is_numeric($lastCheck)) {
            return response()->json([
                'count'     => 0,
                'timestamp' => now()->timestamp * 1000,
            ]);
        }

        $sinceDate = date('Y-m-d H:i:s', intval($lastCheck / 1000));

        $count = Pedido::where('created_at', '>', $sinceDate)
            ->where('estado', 'Por entregar')
            ->count();

        return response()->json([
            'count'     => $count,
            'timestamp' => now()->timestamp * 1000,
        ]);
    }
}
