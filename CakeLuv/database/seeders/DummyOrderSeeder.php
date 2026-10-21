<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DummyOrderSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pelanggan Realistis
        $customers = [
            ['name' => 'Anisa Rahmawati', 'email' => 'anisa.rahma@gmail.com', 'phone' => '081234567801'],
            ['name' => 'Dimas Anggara', 'email' => 'dimas.anggara@gmail.com', 'phone' => '081234567802'],
            ['name' => 'Clarissa Putri', 'email' => 'clarissa.putri@gmail.com', 'phone' => '081234567803'],
            ['name' => 'Rian Firmansyah', 'email' => 'rian.firmansyah@gmail.com', 'phone' => '081234567804'],
            ['name' => 'Nabila Syakieb', 'email' => 'nabila.syakieb@gmail.com', 'phone' => '081234567805'],
            ['name' => 'Kevin Sanjaya', 'email' => 'kevin.sanjaya@gmail.com', 'phone' => '081234567806'],
            ['name' => 'Jessica Mila', 'email' => 'jessica.mila@gmail.com', 'phone' => '081234567807'],
            ['name' => 'Tiara Andini', 'email' => 'tiara.andini@gmail.com', 'phone' => '081234567808'],
            ['name' => 'Reza Rahadian', 'email' => 'reza.rahadian@gmail.com', 'phone' => '081234567809'],
            ['name' => 'Fajar Alfian', 'email' => 'fajar.alfian@gmail.com', 'phone' => '081234567810'],
        ];

        $customerUsers = [];
        foreach ($customers as $c) {
            $customerUsers[] = User::firstOrCreate(
                ['email' => $c['email']],
                [
                    'name' => $c['name'],
                    'password' => Hash::make('password'),
                    'phone' => $c['phone'],
                    'role' => 'customer',
                ]
            );
        }

        $products = Product::all();
        if ($products->isEmpty()) {
            return;
        }

        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();

        // 2. Target Revenue Bulanan Selama 12 Bulan Terakhir (dalam Rupiah)
        // Pola histogram realistis toko kue (Jan - Des)
        $monthlyRevenueTargets = [
            11 => 14500000, // 11 bulan lalu (Nov tahun lalu)
            10 => 22800000, // Des (Hari Raya Natal & Tahun Baru)
            9  => 11500000, // Jan (Normal)
            8  => 18200000, // Feb (Hari Valentine)
            7  => 28500000, // Mar (Peak Puasa / Hampers Lebaran)
            6  => 36400000, // Apr (Peak Idul Fitri)
            5  => 15800000, // Mei
            4  => 17900000, // Jun (Wisuda & Liburan Sekolah)
            3  => 21400000, // Jul (Wedding Season)
            2  => 29800000, // Agu (HUT RI & Acara Korporat)
            1  => 19200000, // Sep
            0  => 24600000, // Okt (Bulan ini)
        ];

        $addresses = [
            'Jl. Senopati No. 42, Kebayoran Baru, Jakarta Selatan',
            'Apartemen Menteng Park Tower Emerald Lt. 15, Jakarta Pusat',
            'Cluster Greenwich Park Blok A5 No. 12, BSD City, Tangerang',
            'Jl. Dago Asri No. 18, Coblong, Bandung',
            'Perumahan CitraLand Bukit Golf Blok D-8, Surabaya Barat',
            'Jl. Setiabudi No. 102, Medan Sunggal, Medan',
        ];

        $messages = [
            'Happy Birthday Mama Tersayang! Sehat & bahagia selalu ❤️',
            'Selamat Ulang Tahun Pernikahan ke-5 sayangku 💕',
            'Happy Graduation! Sukses untuk masa depanmu 🎓',
            'Happy Birthday to the best boss! Dari seluruh tim',
            'Barakallah Fii Umrik adik manis, makin sholehah',
            'Sweet Seventeen! Wish you all the best 🎂✨',
        ];

        $deliveryTypes = ['delivery', 'pickup'];

        $orderSeq = 1001;

        foreach ($monthlyRevenueTargets as $monthsAgo => $targetRevenue) {
            $monthDate = now()->subMonths($monthsAgo)->startOfMonth();
            $daysInMonth = $monthDate->daysInMonth;

            $currentMonthRevenue = 0;
            $orderCount = rand(15, 24);

            for ($i = 0; $i < $orderCount; $i++) {
                $user = $customerUsers[array_rand($customerUsers)];
                $orderDay = rand(1, min(28, $daysInMonth));
                $orderDate = Carbon::create($monthDate->year, $monthDate->month, $orderDay, rand(8, 20), rand(0, 59), rand(0, 59));

                $isPaid = ($monthsAgo > 0) ? (rand(1, 100) <= 95 ? 'paid' : 'pending') : (rand(1, 100) <= 80 ? 'paid' : 'pending');
                $prodStatus = ($isPaid === 'paid') ? (($monthsAgo > 0) ? 'completed' : (rand(1, 2) === 1 ? 'ready' : 'baking')) : 'waiting';

                $orderNumber = 'CKL-' . $orderDate->format('ymd') . '-' . str_pad($orderSeq++, 4, '0', STR_PAD_LEFT);

                // Buat 1 - 3 item per order
                $itemCount = rand(1, 3);
                $totalAmount = 0;
                $itemsData = [];

                for ($it = 0; $it < $itemCount; $it++) {
                    $p = $products->random();
                    $qty = rand(1, 2);
                    // Product price di database adalah ribuan (misal 360 = Rp 360.000)
                    $itemPriceRupiah = $p->price * 1000;
                    $subtotal = $itemPriceRupiah * $qty;
                    $totalAmount += $subtotal;

                    $itemsData[] = [
                        'product_id' => $p->id,
                        'price_at_purchase' => $p->price,
                        'quantity' => $qty,
                        'custom_message' => (rand(1, 10) <= 4) ? $messages[array_rand($messages)] : null,
                    ];
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => $orderNumber,
                    'total_amount' => $totalAmount,
                    'target_date' => $orderDate->copy()->addDays(rand(1, 3))->format('Y-m-d'),
                    'delivery_type' => $deliveryTypes[array_rand($deliveryTypes)],
                    'shipping_address' => $addresses[array_rand($addresses)],
                    'payment_status' => $isPaid,
                    'production_status' => $prodStatus,
                    'snap_token' => 'dummy_snap_token_' . md5($orderNumber),
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                foreach ($itemsData as $item) {
                    OrderItem::create(array_merge($item, [
                        'order_id' => $order->id,
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]));
                }

                $currentMonthRevenue += $totalAmount;
            }
        }
    }
}
