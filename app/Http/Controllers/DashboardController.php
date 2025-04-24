<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $categoryCount = Category::count();
        $itemCount = Item::count();
        $transactionCount = Transaction::count();
        $totalSales = Transaction::sum('total_amount');
        
        return view('dashboard', compact('categoryCount', 'itemCount', 'transactionCount', 'totalSales'));
    }
}