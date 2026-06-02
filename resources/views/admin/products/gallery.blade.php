<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="px-6 py-5 border-b border-slate-100">
        <h2 class="text-base font-semibold text-slate-700">Galeri Gambar</h2>
    </div>
    <div class="p-6">
        @if ($product->productImages->isNotEmpty())
            <div class="grid grid-cols-2 gap-3 mb-4">
                @foreach ($product->productImages as $image)
                    <div class="relative group">
                        <img src="{{ Storage::url($image->fileStorage->link) }}" alt="Gallery image"
                             class="w-full aspect-square object-cover rounded-lg border border-slate-200">
                        <form action="{{ route('admin.products.images.destroy', $image) }}" method="POST"
                              class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity"
                              onsubmit="return confirm('Hapus gambar ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-7 h-7 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors shadow-md">
                                <i class="bi bi-x text-sm"></i>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-slate-400 text-center py-4">Belum ada gambar galeri.</p>
        @endif

        <form action="{{ route('admin.products.images.store', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Upload Gambar Baru</label>
            <input type="file"
                   name="images[]"
                   multiple
                   accept="image/jpeg,image/png,image/webp"
                   class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer mb-3">
            @error('images')
                <p class="text-xs text-red-500 mt-1.5 mb-2">{{ $message }}</p>
            @enderror
            <button type="submit"
                    class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200">
                <i class="bi bi-upload mr-1"></i>
                Upload
            </button>
        </form>
    </div>
</div>
