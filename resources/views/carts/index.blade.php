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
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-md shadow-sm mb-4" role="alert">
                            <div class="flex">
                                <div class="py-1"><svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                                <div>
                                    <p class="font-bold">Sukses</p>
                                    <p class="text-sm">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-md shadow-sm mb-4" role="alert">
                            <div class="flex">
                                <div class="py-1"><svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM10 9v2a1 1 0 0 0 2 0V9a1 1 0 0 0-2 0zm0-5a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/></svg></div>
                                <div>
                                    <p class="font-bold">Error</p>
                                    <p class="text-sm">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Section: Belum Checkout -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Belum Checkout</h3>
                        
                        <form action="{{ route('carts.checkout') }}" method="POST">
                            @csrf
                            <div class="overflow-x-auto bg-white rounded-lg shadow">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <input type="checkbox" id="selectAll" class="form-checkbox h-4 w-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Produk
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Harga
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Jumlah
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Subtotal
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($carts as $cart)
                                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="checkbox" name="cart_ids[]" value="{{ $cart->id }}" class="form-checkbox h-4 w-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 cart-checkbox">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        @if($cart->item->image)
                                                            <img src="{{ asset('storage/' . $cart->item->image) }}" alt="{{ $cart->item->name }}" class="h-10 w-10 rounded-full object-cover mr-3 border border-gray-200">
                                                        @else
                                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">{{ $cart->item->name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">Rp {{ number_format($cart->price, 0, ',', '.') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $cart->quantity }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">Rp {{ number_format($cart->price * $cart->quantity, 0, ',', '.') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <form class="delete-cart-form" data-cart-id="{{ $cart->id }}">
                                                        @csrf
                                                        <button type="button" class="text-red-600 hover:text-red-900 delete-cart-btn transition duration-150 ease-in-out">
                                                            <svg class="h-5 w-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 italic">
                                                    Keranjang belanja kosong.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if($carts->count() > 0)
                                        <tfoot>
                                            <tr class="bg-gray-50">
                                                <td colspan="4" class="px-6 py-4 text-right font-bold text-gray-700">Total:</td>
                                                <td class="px-6 py-4 font-bold text-gray-900">Rp {{ number_format($carts->sum(function($cart) { return $cart->price * $cart->quantity; }), 0, ',', '.') }}</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                            
                            @if($carts->count() > 0)
                                <div class="flex justify-end mt-4">
                                    <button type="submit" id="checkout-btn" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Checkout
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>
                    
                    <!-- Section: Sudah Checkout -->
                    <div class="mt-10">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Sudah Checkout</h3>
                        
                        <div class="overflow-x-auto bg-white rounded-lg shadow">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($checkoutCarts as $cart)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $cart->item->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Rp {{ number_format($cart->price, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $cart->quantity }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">Rp {{ number_format($cart->price * $cart->quantity, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Checkout
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">Belum ada item yang di-checkout</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if($checkoutCarts->count() > 0)
                                    <tfoot>
                                        <tr class="bg-gray-50">
                                            <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-700">Total:</td>
                                            <td class="px-6 py-4 font-bold text-gray-900">Rp {{ number_format($checkoutCarts->sum(function($cart) { return $cart->price * $cart->quantity; }), 0, ',', '.') }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                        
                        @if($checkoutCarts->count() > 0)
                            <div class="mt-8">
                                <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
                                    @csrf
                                    <div class="bg-gray-50 p-6 rounded-lg shadow-md mb-6">
                                        <h4 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Detail Pembayaran</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-1">Total Belanja</label>
                                                <div class="relative rounded-md shadow-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                                    </div>
                                                    <input type="text" id="total_amount" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-12 pr-12 py-3 sm:text-sm border-gray-300 rounded-md bg-gray-100" value="{{ number_format($checkoutCarts->sum(function($cart) { return $cart->price * $cart->quantity; }), 0, ',', '.') }}" readonly>
                                                </div>
                                            </div>
                                            <div>
                                                <label for="paid_amount" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bayar</label>
                                                <div class="relative rounded-md shadow-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                                    </div>
                                                    <input type="number" name="paid_amount" id="paid_amount" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-12 pr-12 py-3 sm:text-sm border-gray-300 rounded-md" min="{{ $checkoutCarts->sum(function($cart) { return $cart->price * $cart->quantity; }) }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-6">
                                            <label for="change_amount" class="block text-sm font-medium text-gray-700 mb-1">Kembalian</label>
                                            <div class="relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="text" id="change_amount" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-12 pr-12 py-3 sm:text-sm border-gray-300 rounded-md bg-gray-100" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="total_amount" value="{{ $checkoutCarts->sum(function($cart) { return $cart->price * $cart->quantity; }) }}">
                                    
                                    <div class="flex justify-end">
                                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md transform hover:scale-105">
                                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                            </svg>
                                            Proses Transaksi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Hapus item dari keranjang dengan AJAX
            $('.delete-cart-btn').on('click', function() {
                const form = $(this).closest('form');
                const cartId = form.data('cart-id');
                
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menghapus item ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
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
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Item berhasil dihapus dari keranjang',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                // Reload halaman untuk memperbarui total
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Terjadi kesalahan: ' + xhr.responseText
                                });
                            }
                        });
                    }
                });
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
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Pilih setidaknya satu item untuk checkout.'
                    });
                    return;
                }
                
                Swal.fire({
                    title: 'Konfirmasi Checkout',
                    text: 'Apakah Anda yakin ingin checkout item yang dipilih?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, checkout!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/carts/checkout',
                            type: 'POST',
                            data: {
                                _token: $('input[name="_token"]').val(),
                                cart_ids: selectedCarts
                            },
                            success: function(response) {
                                // Tampilkan pesan sukses
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Item berhasil di-checkout',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                // Reload halaman untuk memperbarui tampilan
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Terjadi kesalahan: ' + xhr.responseText
                                });
                            }
                        });
                    }
                });
            });
            
            // Hitung kembalian saat jumlah bayar diubah
            $('#paid_amount').on('input', function() {
                const totalAmount = parseInt($('#total_amount').val().replace(/\D/g, ''));
                const paidAmount = parseInt($(this).val()) || 0;
                const changeAmount = paidAmount - totalAmount;
                
                if (changeAmount >= 0) {
                    $('#change_amount').val(new Intl.NumberFormat('id-ID').format(changeAmount));
                } else {
                    $('#change_amount').val('0');
                }
            });
            
            // Validasi form transaksi
            $('#transactionForm').on('submit', function(e) {
                const totalAmount = parseInt($('#total_amount').val().replace(/\D/g, ''));
                const paidAmount = parseInt($('#paid_amount').val()) || 0;
                
                if (paidAmount < totalAmount) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Pembayaran Kurang',
                        text: 'Jumlah bayar harus sama dengan atau lebih dari total belanja!',
                        confirmButtonColor: '#3085d6'
                    });
                } else {
                    // Tampilkan loading saat submit
                    Swal.fire({
                        title: 'Memproses Transaksi',
                        html: 'Mohon tunggu sebentar...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
            });
        });
    </script>
</x-app-layout>