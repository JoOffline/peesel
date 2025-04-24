<!-- Modal untuk edit item -->
<div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel{{ $item->id }}">Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name{{ $item->id }}" class="form-label">Nama Item</label>
                        <input type="text" class="form-control" id="name{{ $item->id }}" name="name" value="{{ $item->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_id{{ $item->id }}" class="form-label">Kategori</label>
                        <select class="form-select" id="category_id{{ $item->id }}" name="category_id" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="price{{ $item->id }}" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="price{{ $item->id }}" name="price" min="0" value="{{ $item->price }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="stock{{ $item->id }}" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stock{{ $item->id }}" name="stock" min="0" value="{{ $item->stock }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description{{ $item->id }}" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description{{ $item->id }}" name="description" rows="3">{{ $item->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image{{ $item->id }}" class="form-label">Gambar</label>
                        @if($item->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        @endif
                        <input type="file" class="form-control" id="image{{ $item->id }}" name="image">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>