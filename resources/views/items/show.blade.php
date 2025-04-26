<!-- ... existing code ... -->

<div class="mt-4">
    <h3 class="text-lg font-semibold">Stok: {{ $item->stock }}</h3>
</div>

<div class="mt-6">
    @if($item->stock > 0)
        <form action="{{ route('carts.store') }}" method="POST">
            @csrf
            <input type="hidden" name="item_id" value="{{ $item->id }}">
            <div class="flex items-center mb-4">
                <label for="quantity" class="mr-2">Jumlah:</label>
                <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $item->stock }}" class="w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Tambah ke Keranjang</button>
        </form>
    @else
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p>Stok habis!</p>
        </div>
        <button disabled class="px-4 py-2 bg-gray-400 text-white rounded-md cursor-not-allowed">Tambah ke Keranjang</button>
    @endif
</div>

<!-- ... existing code ... -->