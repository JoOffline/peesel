<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
USE App\Models\TransactionDetail;

class DashboardController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
  // Di DashboardController.php
public function index()
{
    // Data kategori dan item tetap sama
    $categoryCount = Category::count();
    $itemCount = Item::count();
    
    // Menghitung total transaksi dan total item terjual
    $transactionCount = Transaction::count();
    $totalItemsSold = TransactionDetail::sum('quantity');
    
    // Menghitung total penjualan dari semua transaksi
    $totalSales = Transaction::sum('total_amount');
    
    // Data untuk grafik penjualan mingguan
    $startDate = now()->subDays(6)->startOfDay();
    $endDate = now()->endOfDay();
    
    $salesData = Transaction::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    
    $weeklyLabels = [];
    $weeklySales = [];
    
    // Mengisi data untuk 7 hari terakhir
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i)->format('Y-m-d');
        $weeklyLabels[] = now()->subDays($i)->format('d M');
        
        $sale = $salesData->firstWhere('date', $date);
        $weeklySales[] = $sale ? $sale->total : 0;
    }
    
    return view('dashboard', compact(
        'categoryCount', 
        'itemCount', 
        'transactionCount', 
        'totalItemsSold',
        'totalSales', 
        'weeklyLabels', 
        'weeklySales'
    ));
}
}