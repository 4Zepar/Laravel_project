<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
   public function index()
    {
        // Берем все товары и загружаем их категорию (чтобы не делать лишних запросов)
        $products = Product::with('category')->get();

        // Возвращаем вьюшку 'welcome' и передаем туда переменную $products
        return view('welcome', compact('products'));
    }
}
