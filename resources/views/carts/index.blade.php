<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Daftar Item di Keranjang</h3>
                        <a href="{{ route('transactions.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Kembali ke Transaksi
                        </a>
                    </div>
                    
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($carts as $cart)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                            <div class="flex items-center">
                                                @if ($cart->item->image)
                                                    <img src="{{ asset('storage/' . $cart->item->image) }}" alt="{{ $cart->item->name }}" class="h-10 w-10 rounded-full mr-3">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 mr-3 flex items-center justify-center">
                                                        <span class="text-gray-500">No Img</span>
                                                    </div>
                                                @endif
                                                <div class="text-sm leading-5 font-medium text-gray-900">{{ $cart->item->name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                            <div class="text-sm leading-5 text-gray-900">Rp {{ number_format($cart->price, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                            <form action="{{ route('carts.update', $cart->id) }}" method="POST" class="flex items-center">
                                                @csrf
                                                @method('PUT')
                                                <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1" max="{{ $cart->item->stock + $cart->quantity }}" class="w-16 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <button type="submit" class="ml-2 text-indigo-600 hover:text-indigo-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                            <div class="text-sm leading-5 text-gray-900">Rp {{ number_format($cart->price * $cart->quantity, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium">
                                            <form action="{{ route('carts.destroy', $cart->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus item ini dari keranjang?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-center">
                                            Keranjang belanja kosong.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-bold">Total:</td>
                                    <td class="px-6 py-4 font-bold">Rp {{ number_format($carts->sum(function($cart) { return $cart->price * $cart->quantity; }), 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    @if($carts->count() > 0)
                        <div class="mt-6 flex justify-end">
                            <form action="{{ route('transactions.store') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-3 bg-green-500 text-white rounded-md hover:bg-green-600">
                                    Proses Transaksi
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>