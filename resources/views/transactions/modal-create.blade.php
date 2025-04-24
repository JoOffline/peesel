<!-- Modal untuk menambah transaksi baru -->
<div class="modal fade" id="createTransactionModal" tabindex="-1" aria-labelledby="createTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTransactionModalLabel">Tambah Item ke Keranjang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('carts.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="item_id" class="form-label">Pilih Item</label>
                        <select class="form-select" id="item_id" name="item_id" required>
                            <option value="">Pilih Item</option>
                            @foreach($items ?? [] as $item)
                                <option value="{{ $item->id }}" data-stock="{{ $item->stock }}" data-price="{{ $item->price }}">
                                    {{ $item->name }} - Rp {{ number_format($item->price, 0, ',', '.') }} (Stok: {{ $item->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1" required>
                        <small id="stockWarning" class="text-danger d-none">Jumlah melebihi stok yang tersedia!</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Tambahkan ke Keranjang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const itemSelect = document.getElementById('item_id');
        const quantityInput = document.getElementById('quantity');
        const stockWarning = document.getElementById('stockWarning');
        const submitBtn = document.getElementById('submitBtn');
        
        function checkStock() {
            if (!itemSelect.value) return;
            
            const selectedOption = itemSelect.options[itemSelect.selectedIndex];
            const stock = parseInt(selectedOption.dataset.stock);
            const quantity = parseInt(quantityInput.value);
            
            if (quantity > stock) {
                stockWarning.classList.remove('d-none');
                submitBtn.disabled = true;
            } else {
                stockWarning.classList.add('d-none');
                submitBtn.disabled = false;
            }
        }
        
        itemSelect.addEventListener('change', checkStock);
        quantityInput.addEventListener('input', checkStock);
    });
</script>