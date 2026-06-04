<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCategories = Category::count();
        $totalProducts = Product::count();
        $totalTransactions = Transaction::count();

        $recentProducts = Product::latest()->take(5)->get();
        $recentTransactions = Transaction::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalCategories',
            'totalProducts',
            'totalTransactions',
            'recentProducts',
            'recentTransactions'
        ));
    }
}
