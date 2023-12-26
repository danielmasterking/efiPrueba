<?php


namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $carts = Cart::with('product')->where('user_id', $user->id)->get();

        $consolidatedCart = $carts->groupBy('producto_id')->map(function ($group) {
            $firstCartItem = $group->first();

            return [
                'producto' => $firstCartItem->product->nombre,
                'total_cantidad' => $group->sum('stock'),
                'total_precio' => $group->sum('precio_total'),
            ];
        });

        return response()->json($consolidatedCart->values());
    }

    public function create(Request $request)
    {

        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'stock' => 'required|integer|min:1',
            'precio_total' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $producto = Product::findOrFail($request->producto_id);

        // Verificar si hay suficiente stock
        if ($producto->stock < $request->stock) {
            return response()->json(['message' => 'No hay suficiente stock disponible.'], 422);
        }

        // Crear el carrito si el stock es suficiente
        $cart = Cart::create([
            'user_id' => $user->id,
            'producto' => $request->producto_id,
            'stock' => $request->stock,
            'precio_total' => $request->precio_total,
        ]);

        return response()->json($cart, 201);
    }
}
