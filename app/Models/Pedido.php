<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'cliente_nombre',
        'cliente_apellido',
        'cliente_telefono',
        'garrafones_pedidos',
        'cliente_direccion',
        'cliente_estado',
        'fecha_pedido',
        'metodo_pago',
        'notas',
        'random_value_1',
        'random_value_2',
        'random_value_3'
    ];

    protected $casts = [
        'fecha_pedido' => 'datetime:d/m/Y H:i:s', 
    ];

    public function productos()
    {
        return $this->belongsToMany(Producto::class)
                    ->withPivot('cantidad', 'precio_unitario')
                    ->withTimestamps();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($pedido) {
            $ultimoNumero = static::max('random_value_3') ?? 0;
            $pedido->random_value_3 = $ultimoNumero + 1;
        });
    }
}