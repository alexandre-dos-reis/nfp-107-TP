<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('product.index', [
            'products' => Product::all()
        ]);
    }

    public function detail(int $id): View
    {
        return view('product.detail', [
            'p' => Product::findOrFail($id)
        ]);
    }
}
