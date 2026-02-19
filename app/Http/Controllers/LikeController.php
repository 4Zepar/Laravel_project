<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Product $product)
    {
        $user = Auth::user();
        
        $user->likedProducts()->toggle($product->id);

        return back(); 
    }
}
