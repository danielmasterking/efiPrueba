<?php


namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'activo' => 'sometimes|boolean',
            'precio' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'ean_13' => 'sometimes|string|max:13',
            'page' => 'sometimes|integer|min:1',
            'perPage' => 'sometimes|integer|min:1',
            'orderBy' => [
                'sometimes',
                'string',
                Rule::in(['nombre', 'activo', 'precio', 'stock', 'ean_13']),
            ],
            'orderDirection' => 'sometimes|in:asc,desc',
        ]);

        $query = Product::query();

        if ($request->has('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->has('activo')) {
            $query->where('activo', $request->activo);
        }

        if ($request->has('precio')) {
            $query->where('precio', $request->precio);
        }

        if ($request->has('stock')) {
            $query->where('stock', $request->stock);
        }

        if ($request->has('ean_13')) {
            $query->where('ean_13', $request->ean_13);
        }

        $perPage = $request->input('perPage', 10);
        $page = $request->input('page', 1);

        $orderBy = $request->input('orderBy', 'id');
        $orderDirection = $request->input('orderDirection', 'asc');

        $products = $query->orderBy($orderBy, $orderDirection)->paginate($perPage, ['*'], 'page', $page);

        
        $productData = $products->pluck('nombre', 'activo', 'precio', 'stock', 'ean_13');

        return response()->json([
            'data' => $productData,
            'meta' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
            ],
        ]);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'activo' => 'required|boolean',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'ean_13' => 'required|string|max:13',
        ]);

        $product = Product::create($request->all());
        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'string|max:255',
            'activo' => 'boolean',
            'precio' => 'numeric|min:0',
            'stock' => 'integer|min:0',
            'ean_13' => 'string|max:13',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());
        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(null, 204);
    }
}
