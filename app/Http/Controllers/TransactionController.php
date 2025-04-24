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
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        $items = Item::where('stock', '>', 0)->get(); // Tambahkan ini untuk modal
        return view('transactions.index', compact('transactions', 'items'));
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

    public function store(Request $request)
    {
        // Ambil semua item di keranjang
        $carts = Cart::where('user_id', Auth::id())->with('item')->get();
        
        if ($carts->isEmpty()) {
            return redirect()->route('carts.index')->with('error', 'Keranjang belanja kosong.');
        }
        
        // Periksa stok sekali lagi
        foreach ($carts as $cart) {
            if ($cart->item->stock < $cart->quantity) {
                return redirect()->route('carts.index')->with('error', "Stok {$cart->item->name} tidak mencukupi.");
            }
        }
        
        DB::beginTransaction();
        
        try {
            // Buat transaksi baru
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'total_amount' => $carts->sum(function($cart) {
                    return $cart->price * $cart->quantity;
                }),
                'status' => 'completed',
                'invoice_number' => 'INV-' . time(),
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
                
                // Kurangi stok
                $item = $cart->item;
                $item->stock -= $cart->quantity;
                $item->save();
                
                // Hapus item dari keranjang
                $cart->delete();
            }
            
            DB::commit();
            
            return redirect()->route('transactions.show', $transaction->id)
                ->with('success', 'Transaksi berhasil diproses.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('carts.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}