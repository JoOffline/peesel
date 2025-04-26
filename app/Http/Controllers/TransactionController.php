<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('details.item')->latest();
        
        // Filter berdasarkan tanggal jika ada
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        // Pagination - ubah 10 sesuai kebutuhan jumlah item per halaman
        $transactions = $query->paginate(10);
        
        // Mempertahankan parameter filter saat pagination
        $transactions->appends($request->all());
        
        $items = Item::where('stock', '>', 0)->get();
        
        return view('transactions.index', compact('transactions', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $carts = Cart::with('item')
                    ->where('user_id', Auth::id())
                    ->where('status', 'checkout')
                    ->get();
            
            if ($carts->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada item yang sudah di-checkout!');
            }
            
            // Hitung total belanja
            $totalAmount = $carts->sum(function($cart) {
                return $cart->price * $cart->quantity;
            });
            
            // Validasi jumlah pembayaran
            if ($request->paid_amount < $totalAmount) {
                return redirect()->back()->with('error', 'Jumlah pembayaran kurang dari total belanja.');
            }
            
            // Validasi stok sekali lagi
            foreach ($carts as $cart) {
                if ($cart->quantity > $cart->item->stock) {
                    return redirect()->back()->with('error', "Stok {$cart->item->name} tidak mencukupi!");
                }
            }
            
            // Buat transaksi baru
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'invoice_number' => 'INV-' . time(),
                'total_amount' => $totalAmount,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $request->paid_amount - $totalAmount,
                // Hapus baris status jika kolom tidak ada di database
                // 'status' => 'completed',
            ]);
            
            // Buat detail transaksi dan kurangi stok
            foreach ($carts as $cart) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'item_id' => $cart->item_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->price,
                    'subtotal' => $cart->price * $cart->quantity,
                ]);
                
                // Kurangi stok item
                $item = Item::find($cart->item_id);
                $item->stock -= $cart->quantity;
                $item->save();
            }
            
            // Kosongkan keranjang yang sudah di-checkout
            Cart::where('user_id', Auth::id())
                ->where('status', 'checkout')
                ->delete();
            
            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diproses! Kembalian: Rp ' . number_format($request->paid_amount - $totalAmount, 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function checkout()
    {
        $carts = Cart::with('item')->where('user_id', Auth::id())->get();
        
        if ($carts->isEmpty()) {
            return redirect()->route('carts.index')
                ->with('error', 'Keranjang belanja kosong.');
        }
        
        $total = $carts->sum(function($cart) {
            return $cart->item->price * $cart->quantity;
        });
        
        return view('transactions.checkout', compact('carts', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
        ]);

        $carts = Cart::with('item')->where('user_id', Auth::id())->get();
        
        if ($carts->isEmpty()) {
            return redirect()->route('carts.index')
                ->with('error', 'Keranjang belanja kosong.');
        }
        
        $total = $carts->sum(function($cart) {
            return $cart->item->price * $cart->quantity;
        });
        
        if ($request->paid_amount < $total) {
            return redirect()->back()
                ->with('error', 'Jumlah pembayaran kurang dari total belanja.');
        }

        DB::beginTransaction();
        
        try {
            // Create transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'invoice_number' => 'INV-' . time(),
                'total_amount' => $total,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $request->paid_amount - $total,
            ]);

            // Create transaction details and update stock
            foreach ($carts as $cart) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'item_id' => $cart->item_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->item->price,
                    'subtotal' => $cart->item->price * $cart->quantity,
                ]);

                // Update stock
                $item = Item::find($cart->item_id);
                $item->stock -= $cart->quantity;
                $item->save();
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('transactions.show', $transaction->id)
                ->with('success', 'Transaksi berhasil.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->load('details.item', 'user');
        
        return view('transactions.show', compact('transaction'));
    }

    public function create()
    {
        // Redirect ke halaman keranjang untuk menambahkan item
        return redirect()->route('carts.index');
    }
    
    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);
        
        $transaction->status = $request->status;
        $transaction->save();
        
        return redirect()->route('transactions.index')
            ->with('success', 'Status transaksi berhasil diperbarui!');
    }
    
    // Tambahkan method ini untuk menampilkan form edit status
    public function editStatus(Transaction $transaction)
    {
        return view('transactions.edit_status', compact('transaction'));
    }
    
    /**
     * Calculate total amount from checkout items in cart
     * 
     * @return float
     */
    private function calculateTotalAmount()
    {
        $carts = Cart::with('item')
                ->where('user_id', Auth::id())
                ->where('status', 'checkout')
                ->get();
                
        return $carts->sum(function($cart) {
            return $cart->price * $cart->quantity;
        });
    }
}