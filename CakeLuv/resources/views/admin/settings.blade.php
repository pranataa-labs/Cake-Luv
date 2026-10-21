@extends('layouts.admin')

@section('title', 'Pengaturan Web')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Pengaturan Toko & Website</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                Konfigurasi informasi kontak, alamat bakery, lokasi maps, profil koki, dan konten halaman depan.
            </p>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200/80 dark:border-white/10 bg-white dark:bg-[#0c1222] p-6 shadow-xs">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <!-- Section 1: Informasi Kontak -->
            <div>
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-white/10 mb-4">
                    <i data-lucide="phone-call" class="size-4 text-slate-500 dark:text-slate-400"></i>
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Informasi Kontak & Lokasi</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nomor Telepon (WhatsApp)</label>
                        <input type="text" name="contact_phone" value="{{ \App\Helpers\SiteSettings::get('contact_phone', '+6281234567890') }}" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Alamat Email Toko</label>
                        <input type="email" name="contact_email" value="{{ \App\Helpers\SiteSettings::get('contact_email', 'hello@cakeluv.com') }}" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Alamat Lengkap Toko</label>
                        <textarea name="contact_address" rows="2" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">{{ \App\Helpers\SiteSettings::get('contact_address', 'Jl. Kebon Agung No. 123, Yogyakarta') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Google Maps Embed (Iframe URL / HTML)</label>
                        <textarea name="map_iframe" rows="2" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">{{ \App\Helpers\SiteSettings::get('map_iframe', 'https://www.google.com/maps/embed?pb=...') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 2: Banner & Brand Media -->
            <div>
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-white/10 mb-4">
                    <i data-lucide="image" class="size-4 text-slate-500 dark:text-slate-400"></i>
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Media Banner & Tentang Kami</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Gambar Hero Depan</label>
                        <input type="file" name="hero_image" accept="image/*" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white mb-2">
                        @if(\App\Helpers\SiteSettings::get('hero_image'))
                            <img src="{{ \App\Helpers\SiteSettings::get('hero_image') }}" class="h-20 rounded-lg object-cover border border-slate-200 dark:border-white/10">
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Gambar Tentang Kami</label>
                        <input type="file" name="about_image" accept="image/*" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white mb-2">
                        @if(\App\Helpers\SiteSettings::get('about_image'))
                            <img src="{{ \App\Helpers\SiteSettings::get('about_image') }}" class="h-20 rounded-lg object-cover border border-slate-200 dark:border-white/10">
                        @endif
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Deskripsi Singkat Tentang Kami</label>
                        <textarea name="about_text" rows="3" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">{{ \App\Helpers\SiteSettings::get('about_text', 'Setiap kue yang kami buat dipanggang segar dengan bahan-bahan premium.') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 3: Koki / Patissier -->
            <div>
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-white/10 mb-4">
                    <i data-lucide="award" class="size-4 text-slate-500 dark:text-slate-400"></i>
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Profil Koki (Head Patissier)</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nama Koki</label>
                        <input type="text" name="chef_name" value="{{ \App\Helpers\SiteSettings::get('chef_name', 'Chef Antonio') }}" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Foto Koki</label>
                        <input type="file" name="chef_image" accept="image/*" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white mb-2">
                        @if(\App\Helpers\SiteSettings::get('chef_image'))
                            <img src="{{ \App\Helpers\SiteSettings::get('chef_image') }}" class="h-20 rounded-lg object-cover border border-slate-200 dark:border-white/10">
                        @endif
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Deskripsi Pengalaman Koki</label>
                        <textarea name="chef_desc" rows="2" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-slate-900 dark:focus:ring-white">{{ \App\Helpers\SiteSettings::get('chef_desc', 'Berpengalaman 15 tahun di bidang pastry & artisan bakery di Prancis dan Indonesia.') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4 border-t border-slate-100 dark:border-white/10">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 text-xs font-semibold shadow-xs transition-colors">
                    <i data-lucide="save" class="size-4"></i>
                    <span>Simpan Pengaturan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
