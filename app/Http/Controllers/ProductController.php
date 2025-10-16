<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a paginated list of products.
     */
    public function index(Request $request)
    {
        $products = Product::paginate($request->get('per_page', 10));
        return response()->json($products);
    }

}
