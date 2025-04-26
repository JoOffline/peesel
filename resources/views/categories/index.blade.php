<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Daftar Kategori</h3>
                        <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300 ease-in-out transform hover:scale-105 shadow-md" onclick="toggleCategoryCard()">
                            <i class="fas fa-plus mr-2"></i>Tambah Kategori
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($categories as $index => $category)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">{{ $index + 1 + ($categories->currentPage() - 1) * $categories->perPage() }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap font-medium">{{ $category->name }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-gray-700">{{ $category->description ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm font-medium">
                                            <a href="{{ route('categories.edit', $category->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 bg-indigo-100 px-2 py-1 rounded-md hover:bg-indigo-200 transition duration-200">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            <button onclick="confirmDelete('{{ route('categories.destroy', $category->id) }}')" class="text-red-600 hover:text-red-900 bg-red-100 px-2 py-1 rounded-md hover:bg-red-200 transition duration-200">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                            <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category->id) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-no-wrap text-center text-gray-500">
                                            Tidak ada kategori yang tersedia.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Create Kategori -->
    @include('categories.modal-create')
    
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
                text: "Kategori yang dihapus tidak dapat dikembalikan!",
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

        // Validasi nama kategori yang sudah ada
        document.addEventListener('DOMContentLoaded', function() {
            const categoryForm = document.getElementById('categoryForm');
            const categoryNameInput = document.getElementById('name');
            const existingCategories = @json($categories->pluck('name'));
            
            if (categoryForm) {
                categoryForm.addEventListener('submit', function(e) {
                    const categoryName = categoryNameInput.value.trim();
                    
                    if (existingCategories.includes(categoryName)) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: 'Kategori dengan nama "' + categoryName + '" sudah ada!',
                            showConfirmButton: true
                        });
                    }
                });
            }
        });
    </script>
</x-app-layout>