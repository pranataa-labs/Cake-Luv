<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackendLogicAndSecurityTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customer;
    private Category $category;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@cakeluv.com',
        ]);

        $this->customer = User::factory()->create([
            'role' => 'customer',
            'email' => 'customer@gmail.com',
        ]);

        $this->category = Category::create([
            'name' => 'Signature Cakes',
            'slug' => 'signature-cakes',
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Belgian Dark Truffle Cake',
            'slug' => 'belgian-dark-truffle-cake',
            'description' => 'Rich Belgian chocolate mousse layer cake',
            'price' => 175.00,
            'daily_stock' => 15,
            'is_available' => true,
        ]);
    }

    /* -------------------------------------------------------------------------- */
    /* 1. Security & RBAC Middleware Tests                                        */
    /* -------------------------------------------------------------------------- */

    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_customer_cannot_access_admin_routes(): void
    {
        $response = $this->actingAs($this->customer)->get('/admin/dashboard');
        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'Akses ditolak. Anda bukan admin.');

        $productResponse = $this->actingAs($this->customer)->get('/admin/products');
        $productResponse->assertRedirect('/');

        $ordersResponse = $this->actingAs($this->customer)->get('/admin/orders');
        $ordersResponse->assertRedirect('/');
    }

    public function test_admin_can_access_admin_routes(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertStatus(200);

        $productResponse = $this->actingAs($this->admin)->get('/admin/products');
        $productResponse->assertStatus(200);

        $ordersResponse = $this->actingAs($this->admin)->get('/admin/orders');
        $ordersResponse->assertStatus(200);
    }

    /* -------------------------------------------------------------------------- */
    /* 2. Cart Business Logic Tests                                               */
    /* -------------------------------------------------------------------------- */

    public function test_unauthenticated_user_adding_to_cart_receives_redirect_error(): void
    {
        $response = $this->postJson('/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'error',
                'message' => 'Harap login terlebih dahulu.',
                'redirect' => true,
            ]);
    }

    public function test_authenticated_user_can_add_item_to_cart(): void
    {
        $response = $this->actingAs($this->customer)->postJson('/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 2,
            'custom_message' => 'Happy Graduation!',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Produk ditambahkan ke keranjang belanja.',
            ]);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $this->product->id,
            'quantity' => 2,
            'custom_message' => 'Happy Graduation!',
        ]);
    }

    public function test_user_cannot_add_quantity_exceeding_stock(): void
    {
        $response = $this->actingAs($this->customer)->postJson('/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 999, // daily_stock is 15
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'error',
                'message' => 'Stok produk tidak mencukupi.',
            ]);
    }

    public function test_user_can_update_cart_quantity(): void
    {
        $cart = Cart::create(['user_id' => $this->customer->id]);
        $item = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->customer)->post('/cart/update', [
            'item_id' => $item->id,
            'quantity' => 5,
        ]);

        $response->assertRedirect();
        $this->assertEquals(5, $item->fresh()->quantity);
    }

    public function test_user_cannot_update_cart_quantity_exceeding_stock(): void
    {
        $cart = Cart::create(['user_id' => $this->customer->id]);
        $item = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->customer)->post('/cart/update', [
            'item_id' => $item->id,
            'quantity' => 50, // exceeds 15
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, $item->fresh()->quantity);
    }

    public function test_user_can_remove_item_from_cart(): void
    {
        $cart = Cart::create(['user_id' => $this->customer->id]);
        $item = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->customer)->post('/cart/remove', [
            'item_id' => $item->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    /* -------------------------------------------------------------------------- */
    /* 3. Checkout & Order Processing Logic Tests                                 */
    /* -------------------------------------------------------------------------- */

    public function test_unauthenticated_user_cannot_access_checkout(): void
    {
        $response = $this->get('/checkout');
        $response->assertRedirect('/login');
    }

    public function test_checkout_validation_requires_target_date_and_delivery_type(): void
    {
        $cart = Cart::create(['user_id' => $this->customer->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->customer)->post('/checkout/process', []);
        $response->assertSessionHasErrors(['target_date', 'delivery_type']);
    }

    public function test_checkout_delivery_requires_shipping_address(): void
    {
        $cart = Cart::create(['user_id' => $this->customer->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->customer)->post('/checkout/process', [
            'target_date' => now()->addDays(2)->toDateString(),
            'delivery_type' => 'delivery',
            // missing shipping_address
        ]);

        $response->assertSessionHasErrors('shipping_address');
    }

    public function test_successful_checkout_creates_order_decrements_stock_and_clears_cart(): void
    {
        $initialStock = $this->product->daily_stock; // 15
        $quantity = 2;

        $cart = Cart::create(['user_id' => $this->customer->id]);
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => $quantity,
            'custom_message' => 'Happy Anniversary!',
        ]);

        $targetDate = now()->addDays(3)->toDateString();

        $response = $this->actingAs($this->customer)->post('/checkout/process', [
            'target_date' => $targetDate,
            'delivery_type' => 'pickup',
        ]);

        // Order created
        $order = Order::where('user_id', $this->customer->id)->first();
        $this->assertNotNull($order);
        $this->assertStringStartsWith('ORD-', $order->order_number);
        $this->assertEquals('pending', $order->payment_status);
        $this->assertEquals('waiting', $order->production_status);
        $this->assertEquals('pickup', $order->delivery_type);

        // Order items created with correct snapshot price
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'quantity' => $quantity,
            'custom_message' => 'Happy Anniversary!',
        ]);

        // Stock decremented
        $this->assertEquals($initialStock - $quantity, $this->product->fresh()->daily_stock);

        // Cart items emptied
        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);

        // Redirects to order status page
        $response->assertRedirect(route('checkout.status', $order->order_number));
    }

    public function test_order_status_page_is_viewable(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'order_number' => 'ORD-VIEW-TEST-123',
            'total_amount' => 175000,
            'target_date' => now()->addDays(2)->toDateString(),
            'delivery_type' => 'pickup',
            'payment_status' => 'pending',
            'production_status' => 'waiting',
        ]);

        $response = $this->actingAs($this->customer)->get("/order/status/{$order->order_number}");
        $response->assertStatus(200);
        $response->assertSee('ORD-VIEW-TEST-123');
    }

    /* -------------------------------------------------------------------------- */
    /* 4. Admin Management Logic Tests                                            */
    /* -------------------------------------------------------------------------- */

    public function test_admin_can_update_order_status(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'order_number' => 'ORD-ADMIN-TEST',
            'total_amount' => 150000,
            'target_date' => now()->addDays(1)->toDateString(),
            'delivery_type' => 'pickup',
            'payment_status' => 'pending',
            'production_status' => 'waiting',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/orders/{$order->id}/status", [
            'payment_status' => 'paid',
            'production_status' => 'ready',
        ]);

        $response->assertRedirect();
        $this->assertEquals('paid', $order->fresh()->payment_status);
        $this->assertEquals('ready', $order->fresh()->production_status);
    }

    public function test_admin_can_delete_product(): void
    {
        $response = $this->actingAs($this->admin)->post("/admin/products/{$this->product->id}/delete");
        $response->assertRedirect();
        $this->assertDatabaseMissing('products', ['id' => $this->product->id]);
    }
}
