<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class WelcomeController extends Controller
{
    public function index()
    {
        // Fetch all categories and products from the database
        $categories = Category::all();
        $products = Product::all();

        // Pass both categories and products to the welcome view
        return view('welcome', compact('categories', 'products'));
    }
}
