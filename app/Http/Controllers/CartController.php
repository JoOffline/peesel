<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())
                ->where('status', 'cart')
                ->with('item')
                ->get();
                
        $pendingCarts = Cart::where('user_id', Auth::id())
                ->where('status', 'pending')
                ->with('item')
                ->get();
                
        $checkoutCarts = Cart::where('user_id', Auth::id())
                ->where('status', 'checkout')
                ->with('item')
                ->get();
        
        return view('carts.index', compact('carts', 'pendingCarts', 'checkoutCarts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        // Periksa stok item
        $item = Item::findOrFail($request->item_id);
        
        // Jika stok habis, kembalikan error
        if ($item->stock <= 0) {
            return redirect()->back()->with('error', 'Stok item habis!');
        }
        
        // Jika stok kurang dari jumlah yang diminta, kembalikan error
        if ($item->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $item->stock);
        }
        
        // Cek apakah item sudah ada di keranjang
        $existingCart = Cart::where('user_id', Auth::id())
                            ->where('item_id', $request->item_id)
                            ->where('status', 'cart')
                            ->first();
        
        if ($existingCart) {
            // Jika stok tidak mencukupi untuk total quantity, kembalikan error
            if ($item->stock < ($existingCart->quantity + $request->quantity)) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $item->stock);
            }
            
            // Update quantity jika item sudah ada di keranjang
            $existingCart->quantity += $request->quantity;
            $existingCart->save();
        } else {
            
            Cart::create([
                'user_id' => Auth::id(),
                'item_id' => $request->item_id,
                'quantity' => $request->quantity,
                'price' => $item->price,
                'status' => 'cart', 
            ]);
        }
        
        return redirect()->route('carts.index')->with('success', 'Item berhasil ditambahkan ke keranjang!');
    }
    
    
    public function destroy($id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
        
        if (!$cart) {
            return response()->json(['message' => 'Item tidak ditemukan dalam keranjang.'], 404);
        }
        
        $cart->delete();
        
        if (request()->ajax()) {
            return response()->json(['message' => 'Item berhasil dihapus dari keranjang.']);
        }
        
        return redirect()->route('carts.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        
        // Periksa stok
        $item = $cart->item;
        
        // Jika stok tidak mencukupi, kembalikan error
        if ($item->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $item->stock);
        }
        
        // Update quantity
        $cart->quantity = $request->quantity;
        $cart->save();
        
        return redirect()->route('carts.index')->with('success', 'Jumlah item berhasil diperbarui!');
    }
    
    public function checkout()
    {
        $carts = Cart::where('user_id', Auth::id())
                    ->where('status', 'cart')
                    ->with('item')
                    ->get();
        
        if ($carts->isEmpty()) {
            return redirect()->route('carts.index')->with('error', 'Keranjang kosong!');
        }
        
        // Periksa stok sebelum checkout
        foreach ($carts as $cart) {
            $item = $cart->item;
            
            // Jika stok tidak mencukupi, kembalikan error
            if ($item->stock < $cart->quantity) {
                return redirect()->route('carts.index')->with('error', 'Stok ' . $item->name . ' tidak mencukupi! Stok tersedia: ' . $item->stock);
            }
        }
        
        // Proses checkout dan kurangi stok
        foreach ($carts as $cart) {
            // Kurangi stok
            $item = $cart->item;
            $item->stock -= $cart->quantity;
            $item->save();
            
            // Update status cart menjadi checkout
            $cart->status = 'checkout';
            $cart->save();
            
            // Buat transaksi (jika diperlukan)
            // ... kode untuk membuat transaksi ...
        }
        
        return redirect()->route('transactions.index')->with('success', 'Checkout berhasil!');
    }
}