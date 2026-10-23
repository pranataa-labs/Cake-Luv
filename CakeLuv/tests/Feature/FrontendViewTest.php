<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendViewTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;
    private Category $category;
    private Product $cake;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create([
            'role' => 'customer',
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);

        $this->category = Category::create([
            'name' => 'Boutique Cakes',
            'slug' => 'boutique-cakes',
        ]);

        $this->cake = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Signature Tiramisu Gateau',
            'slug' => 'signature-tiramisu-gateau',
            'description' => 'Infused with premium espresso and mascarpone cream',
            'price' => 195.00,
            'daily_stock' => 12,
            'is_available' => true,
            'image_url' => 'https://example.com/tiramisu.jpg',
        ]);
    }

    /**
     * Test Home page view rendering and key marketing elements.
     */
    public function test_home_page_renders_hero_and_best_sellers(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Patisserie Premium');
        $response->assertSee('Seni Rasa dalam');
        $response->assertSee('LIHAT KATALOG');
        $response->assertSee('Koleksi Terlaris');
    }

    /**
     * Test Shop catalog view rendering, category filters, and products.
     */
    public function test_shop_catalog_renders_products_and_filters(): void
    {
        $response = $this->get('/shop');

        $response->assertStatus(200);
        $response->assertSee('Katalog Produk');
        $response->assertSee('Koleksi');
        $response->assertSee('CakeLuv');
        $response->assertSee('Boutique Cakes');
        $response->assertSee('Signature Tiramisu Gateau');
        $response->assertSee('195.000');
    }

    /**
     * Test category filter on shop page.
     */
    public function test_shop_catalog_filters_by_category(): void
    {
        $anotherCategory = Category::create([
            'name' => 'Cookies & Macarons',
            'slug' => 'cookies-macarons',
        ]);

        $cookie = Product::create([
            'category_id' => $anotherCategory->id,
            'name' => 'Vanilla Macarons Box',
            'slug' => 'vanilla-macarons-box',
            'description' => 'Crisp french macarons',
            'price' => 85.00,
            'daily_stock' => 20,
            'is_available' => true,
        ]);

        $filteredResponse = $this->get('/shop?category=cookies-macarons');

        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Vanilla Macarons Box');
    }

    /**
     * Test Product detail page renders attributes and custom message input.
     */
    public function test_product_detail_page_renders_correctly(): void
    {
        $response = $this->get("/shop/{$this->cake->slug}");

        $response->assertStatus(200);
        $response->assertSee('Signature Tiramisu Gateau');
        $response->assertSee('Boutique Cakes');
        $response->assertSee('Infused with premium espresso');
        $response->assertSee('195.000');
        $response->assertSee('Stok: 12');
        $response->assertSee('Kuantitas');
        $response->assertSee('Pesan di Atas Kue');
        $response->assertSee('TAMBAH KE KERANJANG');
    }

    /**
     * Test Product detail 404 on missing slug.
     */
    public function test_product_detail_returns_404_when_product_not_found(): void
    {
        $response = $this->get('/shop/non-existent-cake-xyz');
        $response->assertStatus(404);
    }

    /**
     * Test Auth: Login view renders form controls.
     */
    public function test_login_view_renders_properly(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang Kembali');
        $response->assertSee('Masuk dengan Google');
        $response->assertSee('Alamat Email');
        $response->assertSee('Kata Sandi');
        $response->assertSee('Masuk Sekarang');
    }

    /**
     * Test Auth: Register view renders proper fields.
     */
    public function test_register_view_renders_properly(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Buat Akun Baru');
        $response->assertSee('Nama Lengkap');
        $response->assertSee('Alamat Email');
        $response->assertSee('Kata Sandi');
        $response->assertSee('Konfirmasi Kata Sandi');
    }

    /**
     * Test Cart view renders for authenticated user.
     */
    public function test_cart_view_renders_for_authenticated_user(): void
    {
        $cart = Cart::create(['user_id' => $this->customer->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->cake->id,
            'quantity' => 1,
            'custom_message' => 'Happy Birthday Mom!',
        ]);

        $response = $this->actingAs($this->customer)->get('/cart');

        $response->assertStatus(200);
        $response->assertSee('Keranjang Belanja');
        $response->assertSee('Signature Tiramisu Gateau');
        $response->assertSee('Happy Birthday Mom!');
    }

    /**
     * Test Checkout view renders items, target date, and delivery options.
     */
    public function test_checkout_view_renders_with_cart_items(): void
    {
        $cart = Cart::create(['user_id' => $this->customer->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->cake->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->customer)->get('/checkout');

        $response->assertStatus(200);
        $response->assertSee('Proses');
        $response->assertSee('Pembayaran');
        $response->assertSee('Tanggal Pengiriman/Pengambilan');
        $response->assertSee('Tipe Pengiriman');
    }
}
