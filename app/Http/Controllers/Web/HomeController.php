<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Obtener productos destacados (los más recientes)
        $featuredProducts = Product::with('category')
            ->latest()
            ->take(8)
            ->get();

        // Obtener categorías principales
        $categories = Category::take(6)->get();

        return view('pages.home', compact('featuredProducts', 'categories'));
    }
}
