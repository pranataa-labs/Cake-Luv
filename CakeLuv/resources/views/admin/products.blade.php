@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Manajemen Produk</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                Kelola katalog kue artisan, lilin ulang tahun, harga, dan ketersediaan stok harian.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <select onchange="window.location.href=this.value" class="px-3 py-2 rounded-lg border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 text-xs font-medium focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">
                <option value="{{ route('admin.products.index', ['sort' => 'desc']) }}" {{ $sort == 'desc' ? 'selected' : '' }}>Waktu: Terbaru</option>
                <option value="{{ route('admin.products.index', ['sort' => 'asc']) }}" {{ $sort == 'asc' ? 'selected' : '' }}>Waktu: Terlama</option>
            </select>
            <button onclick="openModal('modal-add')" class="inline-flex items-center justify-center gap-1.5 h-9 px-3.5 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-semibold shadow-2xs hover:bg-slate-800 dark:hover:bg-slate-100 transition-colors">
                <i data-lucide="plus" class="size-3.5"></i>
                <span>Tambah Produk</span>
            </button>
        </div>
    </div>

    @foreach(['Daftar Kue Artisan' => $cakes, 'Daftar Aksesoris & Lilin' => $candles] as $title => $products)
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ $title }}</h3>
            <span class="text-xs text-slate-500 dark:text-slate-400">{{ count($products) }} item terdaftar</span>
        </div>

        <div class="rounded-xl border border-slate-200/80 dark:border-white/10 bg-white dark:bg-[#0c1222] shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap text-xs text-left">
                    <thead class="bg-slate-50/80 dark:bg-slate-900/60 text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-200/80 dark:border-white/10">
                        <tr>
                            <th class="px-5 py-3.5">Produk</th>
                            <th class="px-5 py-3.5">Kategori</th>
                            <th class="px-5 py-3.5">Harga</th>
                            <th class="px-5 py-3.5 text-center">Stok Harian</th>
                            <th class="px-5 py-3.5 text-center">Ditambahkan</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($products as $product)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-900/40 transition-colors">
                            <td class="px-5 py-3.5 flex items-center">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="size-10 object-cover rounded-lg mr-3 border border-slate-200 dark:border-white/10 shrink-0">
                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-900 dark:text-slate-100 truncate">{{ $product->name }}</p>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 w-52 truncate">{{ $product->description }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600 dark:text-slate-400">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/10">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white">
                                Rp {{ number_format($product->price * 1000, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold {{ $product->daily_stock > 5 ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40' : 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/40' }}">
                                    {{ $product->daily_stock }} unit
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center text-slate-500 dark:text-slate-400 text-[11px]">
                                {{ $product->created_at ? $product->created_at->format('d M Y') : '-' }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <button onclick="openModal('modal-edit-{{ $product->id }}')" class="px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-md text-xs font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div id="modal-edit-{{ $product->id }}" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs hidden flex items-center justify-center z-50 p-4">
                            <div class="bg-white dark:bg-[#0c1222] border border-slate-200 dark:border-white/10 rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-xl transform transition-all">
                                <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex justify-between items-center sticky top-0 bg-white dark:bg-[#0c1222] z-10">
                                    <h3 class="font-bold text-sm text-slate-900 dark:text-white">Edit Produk</h3>
                                    <button onclick="closeModal('modal-edit-{{ $product->id }}')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1">
                                        <i data-lucide="x" class="size-4"></i>
                                    </button>
                                </div>
                                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-6 text-left whitespace-normal">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nama Produk</label>
                                            <input type="text" name="name" value="{{ $product->name }}" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Kategori</label>
                                            <select name="category_id" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Harga (dalam ribuan, misal 360)</label>
                                            <input type="number" name="price" value="{{ $product->price }}" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Stok Harian</label>
                                            <input type="number" name="daily_stock" value="{{ $product->daily_stock }}" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Deskripsi</label>
                                            <textarea name="description" rows="3" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">{{ $product->description }}</textarea>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Gambar Baru (Opsional)</label>
                                            <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">
                                            <p class="text-[11px] text-slate-400 mt-1">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100 dark:border-white/10">
                                        <button type="button" onclick="closeModal('modal-edit-{{ $product->id }}')" class="px-4 py-2 rounded-lg border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-medium transition-colors">Batal</button>
                                        <button type="submit" class="px-4 py-2 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 text-xs font-semibold transition-colors">Update Produk</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">Belum ada produk untuk kategori ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modal Add -->
    <div id="modal-add" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-[#0c1222] border border-slate-200 dark:border-white/10 rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-xl transform transition-all">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex justify-between items-center sticky top-0 bg-white dark:bg-[#0c1222] z-10">
                <h3 class="font-bold text-sm text-slate-900 dark:text-white">Tambah Produk Baru</h3>
                <button onclick="closeModal('modal-add')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nama Produk</label>
                        <input type="text" name="name" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Kategori</label>
                        <select name="category_id" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Harga (dalam ribuan, misal 360)</label>
                        <input type="number" name="price" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Stok Harian</label>
                        <input type="number" name="daily_stock" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Deskripsi</label>
                        <textarea name="description" rows="3" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Gambar Produk</label>
                        <input type="file" name="image" accept="image/*" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100 dark:border-white/10">
                    <button type="button" onclick="closeModal('modal-add')" class="px-4 py-2 rounded-lg border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-medium transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 text-xs font-semibold transition-colors">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>
@endpush
