<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images'])
            ->where('is_active', true);

        // Filtro por categoría
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Búsqueda por nombre, descripción o SKU
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        // Filtro por precio mínimo
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        // Filtro por precio máximo
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filtro por stock
        if ($request->filled('in_stock')) {
            $query->where('stock', '>', 0);
        }

        // Ordenamiento
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('pages.products', compact('products', 'categories'));
    }
    public function show(Product $product)
    {
        // Cargar relaciones
        $product->load(['images', 'category']);
        
        // Obtener productos relacionados (misma categoría, excluyendo el actual)
        $relatedProducts = Product::with(['images', 'category'])
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->take(4)
            ->get();
        
        // Si no hay suficientes productos de la misma categoría, agregar productos destacados
        if ($relatedProducts->count() < 4) {
            $additionalProducts = Product::with(['images', 'category'])
                ->where('is_active', true)
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id'))
                ->where('is_featured', true)
                ->take(4 - $relatedProducts->count())
                ->get();
            
            $relatedProducts = $relatedProducts->merge($additionalProducts);
        }
        
        return view('pages.product-detail', compact('product', 'relatedProducts'));
    }

    // ===== MÉTODOS PARA EL ADMIN PANEL =====

    /**
     * Mostrar listado de productos en el admin
     */
    public function adminIndex(Request $request)
    {
        $query = Product::with(['category', 'images']);

        // Filtro por categoría
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtro por estado
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Búsqueda por nombre, descripción o SKU
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = Category::all();

        // Estadísticas para el admin
        $stats = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'inactive' => Product::where('is_active', false)->count(),
            'low_stock' => Product::where('stock', '<', 10)->count(),
        ];

        return view('admin.products.index', compact('products', 'categories', 'stats'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Guardar nuevo producto
     */
    public function store(Request $request)
    {
        // Asegurar que los checkboxes tengan valor booleano
        $request->merge([
            'is_active' => $request->has('is_active'),
            'is_featured' => $request->has('is_featured')
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Generar el slug a partir del nombre
        $validated['slug'] = Str::slug($validated['name']);

        // Guardar el producto sin main_image primero
        $product = Product::create($validated);

        // Manejo de imágenes
        if ($request->hasFile('images')) {
            $mainImagePath = null;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public'); // Guarda en storage/app/public/products
                // Guardar la primera imagen como principal
                if ($index === 0) {
                    $mainImagePath = $path;
                }
                $product->images()->create([
                    'image_path' => $path,
                    'alt_text' => $product->name,
                    'sort_order' => $index,
                ]);
            }
            // Actualizar el producto con la imagen principal si corresponde
            if ($mainImagePath) {
                $product->main_image = $mainImagePath;
                $product->save();
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Actualizar producto
     */
    public function update(Request $request, Product $product)
    {
        // Asegurar que los checkboxes tengan valor booleano
        $request->merge([
            'is_active' => $request->has('is_active'),
            'is_featured' => $request->has('is_featured')
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Regenerar el slug si el nombre cambió
        $validated['slug'] = Str::slug($validated['name']);

        $product->update($validated);

        // Manejo de imágenes (agregar nuevas)
        if ($request->hasFile('images')) {
            $mainImagePath = null;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                if ($index === 0) {
                    $mainImagePath = $path;
                }
                $product->images()->create([
                    'image_path' => $path,
                    'alt_text' => $product->name,
                    'sort_order' => $index,
                ]);
            }
            if ($mainImagePath) {
                $product->main_image = $mainImagePath;
                $product->save();
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Eliminar producto
     */
    public function destroy(Product $product)
    {
        // Eliminar archivos físicos de imágenes asociadas
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        if ($product->main_image) {
            Storage::disk('public')->delete($product->main_image);
        }
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }
}
