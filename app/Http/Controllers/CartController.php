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
                ->with('item')
                ->get();
        
        return view('carts.index', compact('carts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = Item::findOrFail($request->item_id);
        
        // Periksa stok
        if ($item->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi.');
        }

        // Periksa apakah item sudah ada di keranjang
        $existingCart = Cart::where('user_id', Auth::id())
                        ->where('item_id', $request->item_id)
                        ->first();

        if ($existingCart) {
            // Update jumlah jika item sudah ada di keranjang
            $newQuantity = $existingCart->quantity + $request->quantity;
            
            if ($item->stock < $newQuantity) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi untuk menambah jumlah.');
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
            ]);
        }

        return redirect()->route('carts.index')->with('success', 'Item berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Pastikan cart milik user yang sedang login
        if ($cart->user_id != Auth::id()) {
            return redirect()->route('carts.index')->with('error', 'Anda tidak memiliki akses ke item ini.');
        }

        $item = Item::findOrFail($cart->item_id);
        
        // Periksa stok (perlu menambahkan quantity saat ini karena sudah dialokasikan)
        $availableStock = $item->stock + $cart->quantity;
        
        if ($availableStock < $request->quantity) {
            return redirect()->route('carts.index')->with('error', 'Stok tidak mencukupi.');
        }

        $cart->quantity = $request->quantity;
        $cart->save();

        return redirect()->route('carts.index')->with('success', 'Jumlah item berhasil diperbarui.');
    }

    public function destroy(Cart $cart)
    {
        // Pastikan cart milik user yang sedang login
        if ($cart->user_id != Auth::id()) {
            return redirect()->route('carts.index')->with('error', 'Anda tidak memiliki akses ke item ini.');
        }

        $cart->delete();

        return redirect()->route('carts.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}