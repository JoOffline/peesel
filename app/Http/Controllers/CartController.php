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
        $pendingCarts = Cart::where('user_id', Auth::id())
                ->where('status', 'pending')
                ->with('item')
                ->get();
                
        $checkoutCarts = Cart::where('user_id', Auth::id())
                ->where('status', 'checkout')
                ->with('item')
                ->get();
        
        return view('carts.index', compact('pendingCarts', 'checkoutCarts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $item = Item::findOrFail($request->item_id);
        
        // Validasi stok
        if ($request->quantity > $item->stock) {
            return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia!');
        }
        
        // Cek apakah item sudah ada di keranjang
        $existingCart = Cart::where('user_id', Auth::id())
                            ->where('item_id', $request->item_id)
                            ->where('status', 'pending')
                            ->first();
        
        if ($existingCart) {
            // Update jumlah jika item sudah ada di keranjang
            $newQuantity = $existingCart->quantity + $request->quantity;
            
            // Validasi stok lagi
            if ($newQuantity > $item->stock) {
                return redirect()->back()->with('error', 'Total jumlah melebihi stok yang tersedia!');
            }
            
            $existingCart->quantity = $newQuantity;
            $existingCart->save();
        } else {
            // Tambahkan item baru ke keranjang
            Cart::create([
                'user_id' => Auth::id(),
                'item_id' => $request->item_id,
                'quantity' => $request->quantity,
                'price' => $item->price,
                'status' => 'pending'
            ]);
        }
        
        return redirect()->route('carts.index')->with('success', 'Item berhasil ditambahkan ke keranjang!');
    }
    
    public function checkout(Request $request)
    {
        $cartIds = $request->input('cart_ids', []);
        
        if (empty($cartIds)) {
            return redirect()->back()->with('error', 'Pilih setidaknya satu item untuk checkout.');
        }
        
        // Update status cart menjadi 'checkout'
        Cart::whereIn('id', $cartIds)
            ->where('user_id', Auth::id())
            ->update(['status' => 'checkout']);
        
        return redirect()->route('carts.index')->with('success', 'Item berhasil di-checkout.');
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

    
}