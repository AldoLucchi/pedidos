<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    // Definimos los campos que son asignables
    protected $fillable = [
        'titulo', 'descripcion', 'precio', 'imagen',
    ];

    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class)
                    ->withPivot('cantidad', 'precio_unitario')
                    ->withTimestamps();
    }
}

