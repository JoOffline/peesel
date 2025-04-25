<!-- Card untuk menambah transaksi baru -->
<div id="createTransactionCard" class="hidden mt-4 mb-6">
    <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <h5 class="text-lg font-semibold">Tambah Item ke Keranjang</h5>
            <button type="button" class="text-gray-500 hover:text-gray-700" onclick="toggleTransactionCard()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form action="{{ route('carts.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="item_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Item</label>
                <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="item_id" name="item_id" required>
                    <option value="">Pilih Item</option>
                    @foreach($items ?? [] as $item)
                        <option value="{{ $item->id }}" data-stock="{{ $item->stock }}" data-price="{{ $item->price }}">
                            {{ $item->name }} - Rp {{ number_format($item->price, 0, ',', '.') }} (Stok: {{ $item->stock }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                <input type="number" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="quantity" name="quantity" min="1" value="1" required>
                <p id="stockWarning" class="text-sm text-red-600 mt-1 hidden">Jumlah melebihi stok yang tersedia!</p>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300" onclick="toggleTransactionCard()">Batal</button>
                <button type="submit" id="addToCartButton" class="px-4 py-2 bg-blue-500 text-black rounded-md hover:bg-blue-600">Tambah ke Keranjang</button>
            </div>
        </form>
    </div>
</div>

<!-- Script untuk toggle card dan validasi stok -->
<script>
    function toggleTransactionCard() {
        const card = document.getElementById('createTransactionCard');
        card.classList.toggle('hidden');
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const itemSelect = document.getElementById('item_id');
        const quantityInput = document.getElementById('quantity');
        const stockWarning = document.getElementById('stockWarning');
        const addToCartButton = document.getElementById('addToCartButton');
        
        function validateStock() {
            if (!itemSelect.value) return;
            
            const selectedOption = itemSelect.options[itemSelect.selectedIndex];
            const availableStock = parseInt(selectedOption.dataset.stock);
            const requestedQuantity = parseInt(quantityInput.value);
            
            if (requestedQuantity > availableStock) {
                stockWarning.classList.remove('hidden');
                addToCartButton.disabled = true;
            } else {
                stockWarning.classList.add('hidden');
                addToCartButton.disabled = false;
            }
        }
        
        itemSelect.addEventListener('change', validateStock);
        quantityInput.addEventListener('input', validateStock);
    });
</script>