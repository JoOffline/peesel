<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <h3 class="text-lg font-semibold mb-4">Belum Checkout</h3>
                    
                    <form action="{{ route('carts.checkout') }}" method="POST">
                        @csrf
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            <input type="checkbox" id="selectAll" class="form-checkbox h-4 w-4 text-indigo-600">
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Produk
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Harga
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Jumlah
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Subtotal
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingCarts as $cart)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <input type="checkbox" name="cart_ids[]" value="{{ $cart->id }}" class="form-checkbox h-4 w-4 text-indigo-600 cart-checkbox">
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="flex items-center">
                                                    @if($cart->item->image)
                                                        <img src="{{ asset('storage/' . $cart->item->image) }}" alt="{{ $cart->item->name }}" class="h-10 w-10 rounded-full mr-3">
                                                    @endif
                                                    <div>
                                                        <div class="text-sm leading-5 font-medium text-gray-900">{{ $cart->item->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                Rp {{ number_format($cart->price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                {{ $cart->quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                Rp {{ number_format($cart->price * $cart->quantity, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <!-- Form hapus dengan AJAX -->
                                                <form class="delete-cart-form" data-cart-id="{{ $cart->id }}">
                                                    @csrf
                                                    <button type="button" class="text-red-600 hover:text-red-900 delete-cart-btn">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center">
                                                Keranjang belanja kosong.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if($pendingCarts->count() > 0)
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-right font-bold">Total:</td>
                                            <td class="px-6 py-4 font-bold">Rp {{ number_format($pendingCarts->sum(function($cart) { return $cart->price * $cart->quantity; }), 0, ',', '.') }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                        
                        @if($pendingCarts->count() > 0)
                            <div class="flex justify-end mb-8">
                                <button type="submit" id="checkout-btn" class="px-4 py-2 bg-blue-500 text-gray rounded-md hover:bg-blue-600">
                                    Checkout
                                </button>
                            </div>
                        @endif
                    </form>
                    
                    <h3 class="text-lg font-semibold mb-4">Sudah Checkout</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Produk
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Harga
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Jumlah
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Subtotal
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($checkoutCarts as $cart)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                            <div class="flex items-center">
                                                @if($cart->item->image)
                                                    <img src="{{ asset('storage/' . $cart->item->image) }}" alt="{{ $cart->item->name }}" class="h-10 w-10 rounded-full mr-3">
                                                @endif
                                                <div>
                                                    <div class="text-sm leading-5 font-medium text-gray-900">{{ $cart->item->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                            Rp {{ number_format($cart->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                            {{ $cart->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                            Rp {{ number_format($cart->price * $cart->quantity, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Checkout
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center">
                                            Belum ada item yang di-checkout.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($checkoutCarts->count() > 0)
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right font-bold">Total:</td>
                                        <td class="px-6 py-4 font-bold">Rp {{ number_format($checkoutCarts->sum(function($cart) { return $cart->price * $cart->quantity; }), 0, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    
                    @if($checkoutCarts->count() > 0)
                        <div class="mt-6 flex justify-end">
                            <form action="{{ route('transactions.store') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-3 bg-green-500 text-white rounded-md hover:bg-green-600">
                                    Proses Transaksi
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Hapus item dari keranjang dengan AJAX
            $('.delete-cart-btn').on('click', function() {
                const form = $(this).closest('form');
                const cartId = form.data('cart-id');
                
                if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                    $.ajax({
                        url: '/carts/' + cartId,
                        type: 'DELETE',
                        data: {
                            _token: $('input[name="_token"]').val()
                        },
                        success: function(response) {
                            // Hapus baris item dari tampilan
                            form.closest('tr').remove();
                            // Tampilkan pesan sukses
                            alert('Item berhasil dihapus dari keranjang');
                            // Opsional: Perbarui total atau jumlah item jika diperlukan
                            location.reload(); // Reload halaman untuk memperbarui total
                        },
                        error: function(xhr) {
                            alert('Terjadi kesalahan: ' + xhr.responseText);
                        }
                    });
                }
            });
            
            // Select all checkbox
            $('#selectAll').on('change', function() {
                $('.cart-checkbox').prop('checked', $(this).prop('checked'));
            });
            
            // Checkout dengan AJAX
            $('#checkout-btn').on('click', function(e) {
                e.preventDefault();
                const selectedCarts = [];
                
                // Kumpulkan ID cart yang dipilih
                $('.cart-checkbox:checked').each(function() {
                    selectedCarts.push($(this).val());
                });
                
                if (selectedCarts.length === 0) {
                    alert('Pilih setidaknya satu item untuk checkout.');
                    return;
                }
                
                $.ajax({
                    url: '/carts/checkout',
                    type: 'POST',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        cart_ids: selectedCarts
                    },
                    success: function(response) {
                        // Tampilkan pesan sukses
                        alert('Item berhasil di-checkout');
                        // Reload halaman untuk memperbarui tampilan
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
</x-app-layout>