<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Item') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Daftar Item</h3>
                        <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300 ease-in-out transform hover:scale-105 shadow-md" onclick="toggleItemCard()">
                            <i class="fas fa-plus mr-2"></i>Tambah Item
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Harga</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Stok</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($items as $item)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="flex items-center">
                                                @if ($item->image)
                                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="h-10 w-10 rounded-full mr-3 object-cover border border-gray-200">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 mr-3 flex items-center justify-center">
                                                        <span class="text-gray-500 text-xs">No Img</span>
                                                    </div>
                                                @endif
                                                <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $item->category->name }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="text-sm text-gray-900 font-semibold">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            @if($item->stock > 10)
                                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $item->stock }}</span>
                                            @elseif($item->stock > 0)
                                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">{{ $item->stock }}</span>
                                            @else
                                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Habis</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm font-medium">
                                            <a href="{{ route('items.edit', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 bg-indigo-100 px-2 py-1 rounded-md hover:bg-indigo-200 transition duration-200">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            <button onclick="confirmDelete('{{ route('items.destroy', $item->id) }}')" class="text-red-600 hover:text-red-900 bg-red-100 px-2 py-1 rounded-md hover:bg-red-200 transition duration-200">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                            <form id="delete-form-{{ $item->id }}" action="{{ route('items.destroy', $item->id) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-no-wrap text-center text-gray-500">
                                            Tidak ada item yang tersedia.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('items.modal-create')

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        // Tampilkan SweetAlert untuk pesan sukses
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        // Tampilkan SweetAlert untuk pesan error
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                showConfirmButton: true
            });
        @endif

        // Fungsi untuk konfirmasi hapus dengan SweetAlert
        function confirmDelete(deleteUrl) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Item yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form untuk submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl;
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Validasi nama item yang sudah ada
        document.addEventListener('DOMContentLoaded', function() {
            const itemForm = document.getElementById('itemForm');
            const itemNameInput = document.getElementById('name');
            const existingItems = @json($items->pluck('name'));
            
            if (itemForm) {
                itemForm.addEventListener('submit', function(e) {
                    const itemName = itemNameInput.value.trim();
                    
                    if (existingItems.includes(itemName)) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: 'Item dengan nama "' + itemName + '" sudah ada!',
                            showConfirmButton: true
                        });
                    }
                });
            }
        });
    </script>
</x-app-layout>


<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach ($items as $item)
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-gray-200">
            <h3 class="text-lg font-semibold">{{ $item->name }}</h3>
            <p class="text-gray-600 mt-2">{{ $item->description }}</p>
            <p class="text-gray-800 font-semibold mt-2">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
            <p class="text-gray-800 mt-2">Stok: {{ $item->stock }}</p>
            <div class="mt-4">
                
                
                @if($item->stock > 0)
                    <form action="{{ route('carts.store') }}" method="POST" class="inline-block ml-2">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">Tambah ke Keranjang</button>
                    </form>
                @else
                    <button disabled class="px-4 py-2 bg-gray-400 text-white rounded-md cursor-not-allowed ml-2">Stok Habis</button>
                @endif
            </div>
        </div>
    @endforeach
</div>