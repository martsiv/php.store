<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        $data = Product::all();
        return response()->json($data)
            ->header('Content-Type', 'application/json; charset=utf-8');
    }
}
