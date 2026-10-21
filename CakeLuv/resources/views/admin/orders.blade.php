@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Manajemen Pesanan</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                Kelola status pembayaran dan tahapan produksi pesanan kue pelanggan.
            </p>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200/80 dark:border-white/10 bg-white dark:bg-[#0c1222] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-xs text-left">
                <thead class="bg-slate-50/80 dark:bg-slate-900/60 text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-200/80 dark:border-white/10">
                    <tr>
                        <th class="px-5 py-3.5">No. Pesanan</th>
                        <th class="px-5 py-3.5">Pelanggan</th>
                        <th class="px-5 py-3.5">Total Bayar</th>
                        <th class="px-5 py-3.5 text-center">Pengiriman</th>
                        <th class="px-5 py-3.5 text-center">Status Bayar</th>
                        <th class="px-5 py-3.5 text-center">Status Produksi</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-900/40 transition-colors">
                        <td class="px-5 py-3.5 font-medium text-slate-900 dark:text-slate-100">
                            <span class="font-mono font-bold">{{ $order->order_number }}</span>
                            <span class="block text-[10px] text-slate-400 font-normal">{{ $order->created_at ? $order->created_at->format('d M Y, H:i') : '-' }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $order->user->name ?? 'Guest User' }}</span>
                            <span class="block text-[10px] text-slate-400">{{ $order->user->email ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-3.5 font-bold text-slate-900 dark:text-white">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium uppercase tracking-wider {{ $order->delivery_type == 'delivery' ? 'bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-300 border border-sky-200/60 dark:border-sky-800/40' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/10' }}">
                                {{ $order->delivery_type }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $order->payment_status == 'paid' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40' : 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40' }}">
                                {{ strtoupper($order->payment_status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @php
                                $pColors = [
                                    'waiting' => 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border-amber-200/60 dark:border-amber-800/40',
                                    'baking' => 'bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-300 border-sky-200/60 dark:border-sky-800/40',
                                    'ready' => 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border-indigo-200/60 dark:border-indigo-800/40',
                                    'completed' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200/60 dark:border-emerald-800/40',
                                ];
                                $pNames = [
                                    'waiting' => 'MENUNGGU',
                                    'baking' => 'DIPANGGANG',
                                    'ready' => 'SIAP',
                                    'completed' => 'SELESAI',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium border {{ $pColors[$order->production_status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                {{ $pNames[$order->production_status] ?? strtoupper($order->production_status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <button onclick="openModal('modal-{{ $order->id }}')" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors">
                                <i data-lucide="edit-2" class="size-3"></i>
                                <span>Update</span>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Update Status -->
                    <div id="modal-{{ $order->id }}" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs hidden flex items-center justify-center z-50 p-4">
                        <div class="bg-white dark:bg-[#0c1222] border border-slate-200 dark:border-white/10 rounded-2xl w-full max-w-md overflow-hidden shadow-xl transform transition-all">
                            <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex justify-between items-center">
                                <div>
                                    <h3 class="font-bold text-sm text-slate-900 dark:text-white">Update Status Pesanan</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $order->order_number }}</p>
                                </div>
                                <button onclick="closeModal('modal-{{ $order->id }}')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1">
                                    <i data-lucide="x" class="size-4"></i>
                                </button>
                            </div>
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="p-6 space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Status Pembayaran</label>
                                    <select name="payment_status" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">
                                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                        <option value="expired" {{ $order->payment_status == 'expired' ? 'selected' : '' }}>Expired</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Status Produksi</label>
                                    <select name="production_status" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">
                                        <option value="waiting" {{ $order->production_status == 'waiting' ? 'selected' : '' }}>Menunggu (Waiting)</option>
                                        <option value="baking" {{ $order->production_status == 'baking' ? 'selected' : '' }}>Dipanggang (Baking)</option>
                                        <option value="ready" {{ $order->production_status == 'ready' ? 'selected' : '' }}>Siap (Ready)</option>
                                        <option value="completed" {{ $order->production_status == 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                                    </select>
                                </div>
                                <div class="flex justify-end gap-2 pt-2">
                                    <button type="button" onclick="closeModal('modal-{{ $order->id }}')" class="px-4 py-2 rounded-lg border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-medium transition-colors">Batal</button>
                                    <button type="submit" class="px-4 py-2 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 text-xs font-semibold transition-colors">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-400">Belum ada pesanan masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-slate-200/80 dark:border-white/10">
            {{ $orders->links() }}
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
