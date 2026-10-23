<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseArchitectureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test all core e-commerce and system tables exist.
     */
    public function test_all_expected_database_tables_exist(): void
    {
        $tables = [
            'users',
            'categories',
            'products',
            'carts',
            'cart_items',
            'orders',
            'order_items',
            'settings',
        ];

        foreach ($tables as $table) {
            $this->assertTrue(Schema::hasTable($table), "Table '{$table}' does not exist in schema.");
        }
    }

    /**
     * Test table columns and schemas meet requirements.
     */
    public function test_products_table_has_expected_columns(): void
    {
        $expectedColumns = [
            'id', 'category_id', 'name', 'slug', 'description',
            'price', 'daily_stock', 'image_url', 'is_available',
            'created_at', 'updated_at'
        ];

        $this->assertTrue(Schema::hasColumns('products', $expectedColumns));
    }

    public function test_orders_table_has_expected_columns(): void
    {
        $expectedColumns = [
            'id', 'user_id', 'order_number', 'total_amount',
            'target_date', 'delivery_type', 'shipping_address',
            'payment_status', 'production_status', 'snap_token',
            'created_at', 'updated_at'
        ];

        $this->assertTrue(Schema::hasColumns('orders', $expectedColumns));
    }

    public function test_order_items_table_has_expected_columns(): void
    {
        $expectedColumns = [
            'id', 'order_id', 'product_id', 'price_at_purchase',
            'quantity', 'custom_message', 'created_at', 'updated_at'
        ];

        $this->assertTrue(Schema::hasColumns('order_items', $expectedColumns));
    }

    /**
     * Test relational cascade: Deleting a Cart deletes its CartItems.
     */
    public function test_cart_deletion_cascades_to_cart_items(): void
    {
        $user = User::factory()->create();
        $cart = Cart::create(['user_id' => $user->id]);
        $product = Product::create([
            'name' => 'Red Velvet Cake',
            'slug' => 'red-velvet-cake',
            'price' => 150000,
            'daily_stock' => 10,
        ]);

        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'custom_message' => 'Happy Birthday!',
        ]);

        $this->assertDatabaseHas('cart_items', ['id' => $cartItem->id]);

        $cart->delete();

        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }

    /**
     * Test relational cascade: Deleting an Order deletes its OrderItems.
     */
    public function test_order_deletion_cascades_to_order_items(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-TEST-001',
            'total_amount' => 250000.00,
            'target_date' => now()->addDays(2)->toDateString(),
            'delivery_type' => 'pickup',
            'payment_status' => 'pending',
            'production_status' => 'waiting',
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'price_at_purchase' => 250000.00,
            'quantity' => 1,
            'custom_message' => 'Anniversary Cake',
        ]);

        $this->assertDatabaseHas('order_items', ['id' => $item->id]);

        $order->delete();

        $this->assertDatabaseMissing('order_items', ['id' => $item->id]);
    }

    /**
     * Test historical data preservation:
     * When a product is deleted, order_items must NOT be deleted.
     * The product_id should be set to null, preserving purchase price and historical record.
     */
    public function test_deleting_product_preserves_order_item_history_with_null_product_id(): void
    {
        $product = Product::create([
            'name' => 'Matcha Opera Cake',
            'slug' => 'matcha-opera-cake',
            'price' => 275000.50,
            'daily_stock' => 5,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-TEST-002',
            'total_amount' => 275000.50,
            'target_date' => now()->addDays(1)->toDateString(),
            'delivery_type' => 'delivery',
            'payment_status' => 'paid',
            'production_status' => 'completed',
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'price_at_purchase' => 275000.50,
            'quantity' => 1,
            'custom_message' => 'Congratulations!',
        ]);

        $this->assertDatabaseHas('order_items', [
            'id' => $orderItem->id,
            'product_id' => $product->id,
            'price_at_purchase' => 275000.50,
        ]);

        // Delete product
        $product->delete();

        // Order item still exists, product_id becomes null, financial history intact
        $this->assertDatabaseHas('order_items', [
            'id' => $orderItem->id,
            'product_id' => null,
            'price_at_purchase' => 275000.50,
            'quantity' => 1,
        ]);
    }

    /**
     * Test Category deletion sets product category_id to null (nullOnDelete).
     */
    public function test_category_deletion_nulls_product_category_id(): void
    {
        $category = Category::create([
            'name' => 'Birthday Cakes',
            'slug' => 'birthday-cakes',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Chocolate Fudge Cake',
            'slug' => 'chocolate-fudge-cake',
            'price' => 180000,
            'daily_stock' => 8,
        ]);

        $this->assertEquals($category->id, $product->fresh()->category_id);

        $category->delete();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'category_id' => null,
        ]);
    }

    /**
     * Test unique constraints on slugs and order_number.
     */
    public function test_unique_constraints_are_enforced(): void
    {
        Category::create(['name' => 'Pastry', 'slug' => 'pastry']);
        $this->expectException(\Illuminate\Database\QueryException::class);
        Category::create(['name' => 'Pastry Duplicate', 'slug' => 'pastry']);
    }

    public function test_product_slug_uniqueness(): void
    {
        Product::create(['name' => 'Cheesecake', 'slug' => 'cheesecake', 'price' => 120000]);
        $this->expectException(\Illuminate\Database\QueryException::class);
        Product::create(['name' => 'Another Cheesecake', 'slug' => 'cheesecake', 'price' => 130000]);
    }

    public function test_order_number_uniqueness(): void
    {
        Order::create([
            'order_number' => 'ORD-UNIQUE-101',
            'total_amount' => 100000,
            'target_date' => now()->toDateString(),
            'delivery_type' => 'pickup',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Order::create([
            'order_number' => 'ORD-UNIQUE-101',
            'total_amount' => 200000,
            'target_date' => now()->toDateString(),
            'delivery_type' => 'pickup',
        ]);
    }

    /**
     * Test financial precision stores decimal values accurately without floating-point distortion.
     */
    public function test_financial_decimal_precision(): void
    {
        $product = Product::create([
            'name' => 'Artisan Cake',
            'slug' => 'artisan-cake',
            'price' => 345678.75,
            'daily_stock' => 3,
        ]);

        $this->assertEquals('345678.75', (string) $product->fresh()->price);
    }
}
