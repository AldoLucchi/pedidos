<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        
        return Inertia::render('Productos/Index', [
            'productos' => $productos
        ]);
    }

        /**
     * Almacenar un nuevo producto.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:500',
            'precio' => 'required|numeric',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
    
        // Procesar la imagen si se ha subido
        if ($request->hasFile('imagen')) {
            $filename = $request->file('imagen')->hashName(); // Obtiene solo el nombre del archivo
            $request->file('imagen')->storeAs('imagenesproductos', $filename, 'public'); // Guarda en storage
    
            $validated['imagen'] = $filename; // Guarda solo el nombre en la base de datos
        }
    
        Producto::create($validated);
    
        return redirect()->route('productos.index');
    }    

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
    
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:500',
            'precio' => 'required|numeric',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
    
        // Eliminar la imagen anterior solo si se sube una nueva
        if ($request->hasFile('imagen')) {
            // Borrar la imagen anterior si existe
            if ($producto->imagen) {
                Storage::disk('public')->delete('imagenesproductos/' . $producto->imagen);
            }
    
            // Guardar la nueva imagen
            $filename = $request->file('imagen')->hashName(); // Obtiene solo el nombre del archivo
            $request->file('imagen')->storeAs('imagenesproductos', $filename, 'public'); // Guarda en storage
    
            $validated['imagen'] = $filename; // Guarda solo el nombre en la base de datos
        } else {
            // Si no se sube una nueva imagen, mantener la imagen actual
            $validated['imagen'] = $producto->imagen;
        }
    
        // Actualizar el producto
        $producto->update($validated);
    
        return to_route('productos.index');
    }

    /**
     * Mostrar el formulario para agregar un nuevo producto.
     */
    public function create()
    {
        return Inertia::render('Productos/Create');
    }

    public function destroy($id)
    {
        // Encuentra el producto por su ID
        $producto = Producto::findOrFail($id);

        // Elimina el producto
        $producto->delete();

        // Redirige a la lista de productos con un mensaje de éxito
        return redirect()->route('productos.index')->with('successMessage', 'Producto eliminado correctamente.');
    }

}

