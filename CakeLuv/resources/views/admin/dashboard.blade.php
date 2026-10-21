@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Dashboard</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                Pantau performa penjualan kue, pesanan pelanggan, dan mutasi transaksi CakeLuv secara realtime.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.orders.index') }}" 
               class="inline-flex items-center justify-center gap-1.5 h-9 px-3.5 rounded-lg border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 text-xs font-medium shadow-2xs hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                <i data-lucide="shopping-bag" class="size-3.5"></i>
                <span>Kelola Pesanan</span>
            </a>
            <a href="{{ route('admin.products.index') }}" 
               class="inline-flex items-center justify-center gap-1.5 h-9 px-3.5 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-medium shadow-2xs hover:bg-slate-800 dark:hover:bg-slate-100 transition-colors">
                <i data-lucide="plus" class="size-3.5"></i>
                <span>Tambah Produk</span>
            </a>
        </div>
    </div>

    <!-- Tabs List (Shadcn style tabs) -->
    <div class="border-b border-slate-200/80 dark:border-white/10 pb-px">
        <div class="inline-flex h-9 items-center rounded-lg bg-slate-100 dark:bg-slate-900/80 p-1 text-xs text-slate-500 dark:text-slate-400">
            <button type="button" class="inline-flex items-center justify-center gap-1.5 px-3 py-1 font-semibold rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-2xs transition-all">
                <i data-lucide="layout-dashboard" class="size-3.5"></i>
                <span>Overview</span>
            </button>
            <button type="button" class="inline-flex items-center justify-center gap-1.5 px-3 py-1 font-medium rounded-md hover:text-slate-900 dark:hover:text-slate-200 transition-all">
                <i data-lucide="bar-chart-3" class="size-3.5"></i>
                <span>Analitik</span>
            </button>
            <button type="button" disabled class="inline-flex items-center justify-center gap-1.5 px-3 py-1 font-medium rounded-md opacity-40 cursor-not-allowed">
                <i data-lucide="file-text" class="size-3.5"></i>
                <span>Laporan (Segera)</span>
            </button>
        </div>
    </div>

    <!-- 4 KPI Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- KPI 1: Total Pendapatan -->
        <div class="rounded-xl border border-slate-200/80 dark:border-white/10 bg-white dark:bg-[#0c1222] p-5 shadow-xs transition-all hover:border-slate-300 dark:hover:border-white/20">
            <div class="flex items-center justify-between space-y-0 pb-2">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Pendapatan</span>
                <div class="size-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <i data-lucide="dollar-sign" class="size-4"></i>
                </div>
            </div>
            <div class="space-y-1">
                <div class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                    <span class="inline-flex items-center text-emerald-600 dark:text-emerald-400 font-semibold">
                        <i data-lucide="trending-up" class="size-3.5 me-0.5"></i>
                        +{{ $revenueGrowth }}%
                    </span>
                    <span>dari bulan lalu</span>
                </div>
            </div>
        </div>

        <!-- KPI 2: Total Pesanan -->
        <div class="rounded-xl border border-slate-200/80 dark:border-white/10 bg-white dark:bg-[#0c1222] p-5 shadow-xs transition-all hover:border-slate-300 dark:hover:border-white/20">
            <div class="flex items-center justify-between space-y-0 pb-2">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Pesanan</span>
                <div class="size-8 rounded-lg bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                    <i data-lucide="shopping-bag" class="size-4"></i>
                </div>
            </div>
            <div class="space-y-1">
                <div class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                    {{ number_format($totalOrders, 0, ',', '.') }}
                </div>
                <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                    <span class="inline-flex items-center text-emerald-600 dark:text-emerald-400 font-semibold">
                        <i data-lucide="trending-up" class="size-3.5 me-0.5"></i>
                        +{{ $orderGrowth }}%
                    </span>
                    <span>pesanan masuk</span>
                </div>
            </div>
        </div>

        <!-- KPI 3: Menunggu Pembayaran -->
        <div class="rounded-xl border border-slate-200/80 dark:border-white/10 bg-white dark:bg-[#0c1222] p-5 shadow-xs transition-all hover:border-slate-300 dark:hover:border-white/20">
            <div class="flex items-center justify-between space-y-0 pb-2">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Menunggu Bayar</span>
                <div class="size-8 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <i data-lucide="clock" class="size-4"></i>
                </div>
            </div>
            <div class="space-y-1">
                <div class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                    {{ $pendingOrders }}
                </div>
                <div class="flex items-center gap-1.5 text-xs text-amber-600 dark:text-amber-400 font-medium">
                    <i data-lucide="alert-circle" class="size-3.5"></i>
                    <span>Perlu konfirmasi pembayaran</span>
                </div>
            </div>
        </div>

        <!-- KPI 4: Jumlah Produk -->
        <div class="rounded-xl border border-slate-200/80 dark:border-white/10 bg-white dark:bg-[#0c1222] p-5 shadow-xs transition-all hover:border-slate-300 dark:hover:border-white/20">
            <div class="flex items-center justify-between space-y-0 pb-2">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Katalog Produk</span>
                <div class="size-8 rounded-lg bg-violet-50 dark:bg-violet-950/40 text-violet-600 dark:text-violet-400 flex items-center justify-center">
                    <i data-lucide="cake" class="size-4"></i>
                </div>
            </div>
            <div class="space-y-1">
                <div class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                    {{ $totalProducts }} <span class="text-sm font-normal text-slate-500 dark:text-slate-400">SKU</span>
                </div>
                <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                    <i data-lucide="check" class="size-3.5 text-emerald-500"></i>
                    <span>Aktif & siap diproduksi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Grid Row (7 cols: 4 cols Overview Bar Chart + 3 cols Doughnut Category) -->
    <div class="grid grid-cols-1 lg:grid-cols-7 gap-4">
        
        <!-- Overview Bar Chart (col-span-4) -->
        <div class="lg:col-span-4 rounded-xl border border-slate-200/80 dark:border-white/10 bg-white dark:bg-[#0c1222] p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">Overview Pendapatan</h3>
                    <span class="text-[11px] font-medium px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                        12 Bulan Terakhir
                    </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                    Total omset penjualan bersih dari pesanan yang telah dibayar lunas.
                </p>
            </div>
            <div class="w-full relative h-[300px]">
                <canvas id="overviewChart"></canvas>
            </div>
        </div>

        <!-- Category Distribution Doughnut Chart (col-span-3) -->
        <div class="lg:col-span-3 rounded-xl border border-slate-200/80 dark:border-white/10 bg-white dark:bg-[#0c1222] p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">Distribusi Kategori Produk</h3>
                    <span class="text-[11px] font-medium px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                        Master Data
                    </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                    Proporsi varian SKU kue dan aksesoris per kategori produk.
                </p>
            </div>
            <div class="w-full relative h-[300px] flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Second Grid Row (7 cols: 4 cols Recent Orders + 3 cols Top Selling Products) -->
    <div class="grid grid-cols-1 lg:grid-cols-7 gap-4">
        
        <!-- Recent Orders (col-span-4) -->
        <div class="lg:col-span-4 rounded-xl border border-slate-200/80 dark:border-white/10 bg-white dark:bg-[#0c1222] p-5 shadow-xs flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">Pesanan Terbaru</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Daftar 6 transaksi pesanan terbaru dari pelanggan.
                    </p>
                </div>
                <a href="{{ route('admin.orders.index') }}" 
                   class="text-xs font-semibold text-slate-900 dark:text-slate-200 hover:underline flex items-center gap-1">
                    <span>Lihat Semua</span>
                    <i data-lucide="chevron-right" class="size-3.5"></i>
                </a>
            </div>

            <!-- Orders Table List -->
            <div class="overflow-x-auto -mx-5 px-5">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-white/5 text-slate-400 dark:text-slate-500 font-semibold uppercase tracking-wider">
                            <th class="pb-2.5">Pelanggan</th>
                            <th class="pb-2.5">No. Pesanan</th>
                            <th class="pb-2.5 text-center">Status Bayar</th>
                            <th class="pb-2.5 text-center">Produksi</th>
                            <th class="pb-2.5 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($recentOrders as $order)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-900/40 transition-colors">
                            <td class="py-3 pr-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="size-7 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center font-bold text-[11px] shrink-0 border border-slate-200 dark:border-white/10">
                                        {{ substr($order->user->name ?? 'P', 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-slate-900 dark:text-slate-100 truncate">{{ $order->user->name ?? 'Guest User' }}</p>
                                        <p class="text-[10px] text-slate-400 dark:text-slate-500 truncate">{{ $order->delivery_type == 'delivery' ? 'Pengiriman Kurir' : 'Ambil di Toko' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <span class="font-mono font-medium text-slate-700 dark:text-slate-300">{{ $order->order_number }}</span>
                                <span class="block text-[10px] text-slate-400">{{ $order->created_at ? $order->created_at->diffForHumans() : '-' }}</span>
                            </td>
                            <td class="py-3 px-2 text-center">
                                @if($order->payment_status === 'paid')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                    LUNAS
                                </span>
                                @elseif($order->payment_status === 'pending')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40">
                                    PENDING
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/40">
                                    {{ strtoupper($order->payment_status) }}
                                </span>
                                @endif
                            </td>
                            <td class="py-3 px-2 text-center">
                                @php
                                    $prodColors = [
                                        'waiting' => 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border-amber-200/60 dark:border-amber-800/40',
                                        'baking' => 'bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-300 border-sky-200/60 dark:border-sky-800/40',
                                        'ready' => 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border-indigo-200/60 dark:border-indigo-800/40',
                                        'completed' => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-white/10',
                                    ];
                                    $prodLabels = [
                                        'waiting' => 'Menunggu',
                                        'baking' => 'Dipanggang',
                                        'ready' => 'Siap',
                                        'completed' => 'Selesai',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium border {{ $prodColors[$order->production_status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                    {{ $prodLabels[$order->production_status] ?? ucfirst($order->production_status) }}
                                </span>
                            </td>
                            <td class="py-3 pl-3 text-right">
                                <span class="font-semibold text-slate-900 dark:text-white">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400">
                                Belum ada pesanan masuk.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Selling Cakes (col-span-3) -->
        <div class="lg:col-span-3 rounded-xl border border-slate-200/80 dark:border-white/10 bg-white dark:bg-[#0c1222] p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">Kue Terlaris</h3>
                    <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-slate-900 dark:text-slate-200 hover:underline">
                        Katalog
                    </a>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                    Produk dengan total kuantitas penjualan tertinggi.
                </p>

                <div class="space-y-3.5">
                    @forelse($topProducts as $idx => $item)
                    <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                        <!-- Rank badge -->
                        <div class="size-6 rounded-md {{ $idx === 0 ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 font-bold' : ($idx === 1 ? 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300 font-bold' : 'bg-slate-100 text-slate-500 dark:bg-slate-900 dark:text-slate-400') }} flex items-center justify-center text-xs shrink-0">
                            {{ $idx + 1 }}
                        </div>

                        <!-- Product Thumbnail -->
                        <div class="size-10 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-white/10 shrink-0">
                            @if($item->product && $item->product->image_url)
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="size-full object-cover">
                            @else
                            <div class="size-full flex items-center justify-center text-slate-400">
                                <i data-lucide="cake" class="size-5"></i>
                            </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-900 dark:text-slate-100 truncate">
                                {{ $item->product->name ?? 'Produk Dihapus' }}
                            </p>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500">
                                {{ $item->product->category->name ?? 'Umum' }}
                            </p>
                        </div>

                        <!-- Sold metrics -->
                        <div class="text-right shrink-0">
                            <span class="block text-xs font-bold text-slate-900 dark:text-white">
                                {{ $item->total_sold }} pcs
                            </span>
                            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-medium">
                                Rp {{ number_format($item->total_sales, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 text-center py-6">Belum ada riwayat produk terjual.</p>
                    @endforelse
                </div>
            </div>

            <!-- Footer summary -->
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                <span>Total 4 Status Produksi:</span>
                <span class="font-medium text-slate-800 dark:text-slate-200">
                    {{ $statusBreakdown['completed'] ?? 0 }} Selesai, {{ $statusBreakdown['baking'] ?? 0 }} Dipanggang
                </span>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Function to detect active dark mode
        function isDarkModeActive() {
            return document.documentElement.classList.contains('dark');
        }

        // 1. Overview 12-Month Bar Chart (Shadcn-Admin style)
        const overviewCtx = document.getElementById('overviewChart').getContext('2d');
        const overviewLabels = {!! json_encode($overviewLabels) !!};
        const overviewData = {!! json_encode($overviewData) !!};

        let barColor = isDarkModeActive() ? '#f8fafc' : '#0f172a';
        let gridColor = isDarkModeActive() ? 'rgba(255, 255, 255, 0.08)' : 'rgba(148, 163, 184, 0.12)';
        let textColor = isDarkModeActive() ? '#94a3b8' : '#64748b';

        const overviewChart = new Chart(overviewCtx, {
            type: 'bar',
            data: {
                labels: overviewLabels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: overviewData,
                    backgroundColor: barColor,
                    borderRadius: {
                        topLeft: 6,
                        topRight: 6,
                        bottomLeft: 0,
                        bottomRight: 0
                    },
                    borderSkipped: false,
                    maxBarThickness: 38
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: isDarkModeActive() ? '#0f172a' : '#1e293b',
                        titleColor: '#f8fafc',
                        bodyColor: '#f8fafc',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                return ' Pendapatan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: textColor,
                            font: {
                                family: 'Inter',
                                size: 11,
                                weight: '500'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: textColor,
                            font: {
                                family: 'Inter',
                                size: 10
                            },
                            callback: function (value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(0) + 'jt';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + 'k';
                                }
                                return 'Rp ' + value;
                            }
                        }
                    }
                }
            }
        });

        // 2. Category Distribution Doughnut Chart
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        const catLabels = {!! json_encode($categoryLabels) !!};
        const catData = {!! json_encode($categoryData) !!};

        // OKLCH Palette
        const palette = ['#0ea5e9', '#6366f1', '#10b981', '#f59e0b', '#f43f5e', '#8b5cf6', '#14b8a6', '#ec4899'];

        const categoryChart = new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    label: 'Jumlah Produk',
                    data: catData,
                    backgroundColor: palette.slice(0, catLabels.length),
                    borderWidth: 2,
                    borderColor: isDarkModeActive() ? '#0c1222' : '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 16,
                            color: textColor,
                            font: {
                                family: 'Inter',
                                size: 11,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: isDarkModeActive() ? '#0f172a' : '#1e293b',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                return ` ${context.label}: ${context.parsed} SKU`;
                            }
                        }
                    }
                }
            }
        });

        // Dynamic theme switch listener
        window.addEventListener('theme-changed', function (e) {
            const isDark = e.detail.isDark;
            const newBarColor = isDark ? '#f8fafc' : '#0f172a';
            const newGridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(148, 163, 184, 0.12)';
            const newTextColor = isDark ? '#94a3b8' : '#64748b';

            overviewChart.data.datasets[0].backgroundColor = newBarColor;
            overviewChart.options.scales.x.ticks.color = newTextColor;
            overviewChart.options.scales.y.ticks.color = newTextColor;
            overviewChart.options.scales.y.grid.color = newGridColor;
            overviewChart.update();

            categoryChart.data.datasets[0].borderColor = isDark ? '#0c1222' : '#ffffff';
            categoryChart.options.plugins.legend.labels.color = newTextColor;
            categoryChart.update();
        });
    });
</script>
@endpush
