<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        
        if ($request->has('search')) {
            $products = Product::all();
            $searchInput = $request->get('search');
        } else {
            $products = Product::all();
            $searchInput = "";
        }

        return view('product.index', [
            'products' => $products,
            'searchInput' => $searchInput
        ]);
    }

    public function detail(int $id): View
    {
        return view('product.detail', [
            'p' => Product::findOrFail($id)
        ]);
    }
}
