<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InventorySalesTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role): User
    {
        return User::create([
            'name' => ucfirst($role) . ' Test',
            'email' => $role . uniqid() . '@test.com',
            'password' => Hash::make('password123'),
            'role' => $role,
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_user_can_login_and_see_dashboard(): void
    {
        $user = $this->makeUser('staff');

        $this->post('/login', ['email' => $user->email, 'password' => 'password123'])
            ->assertRedirect(route('dashboard'));

        $this->get('/dashboard')->assertOk()->assertSee('Dashboard');
    }

    public function test_staff_cannot_access_user_management(): void
    {
        $this->actingAs($this->makeUser('staff'))
            ->get('/users')
            ->assertForbidden();
    }

    public function test_admin_can_create_staff_with_role_saved(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)->post('/users', [
            'name' => 'Budi',
            'email' => 'budi@test.com',
            'password' => 'secret123',
            'role' => 'staff',
        ])->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', ['email' => 'budi@test.com', 'role' => 'staff']);
    }

    public function test_product_is_created_with_generated_code_and_price(): void
    {
        $this->actingAs($this->makeUser('staff'))->post('/products', [
            'name' => 'Kopi Bubuk',
            'stock' => 10,
            'price' => 22000,
        ])->assertRedirect(route('products.index'));

        $product = Product::where('name', 'Kopi Bubuk')->firstOrFail();
        $this->assertStringStartsWith('PRD-', $product->code);
        $this->assertEquals(22000, (int) $product->price);
        $this->assertEquals(10, $product->stock);
    }

    public function test_transaction_reduces_stock_and_records_details(): void
    {
        $staff = $this->makeUser('staff');
        $product = Product::create(['name' => 'Pulpen', 'code' => 'PRD-T1', 'stock' => 10, 'price' => 4500]);

        $this->actingAs($staff)->post('/transactions', [
            'product_id' => $product->id,
            'quantity' => 3,
        ])->assertRedirect();

        $this->assertEquals(7, $product->fresh()->stock);

        $sale = Sale::firstOrFail();
        $this->assertEquals(13500, (int) $sale->total_amount);
        $this->assertEquals($staff->id, $sale->user_id);

        $detail = SaleDetail::firstOrFail();
        $this->assertEquals($product->id, $detail->product_id);
        $this->assertEquals(3, $detail->quantity);
        $this->assertEquals(13500, (int) $detail->subtotal);
    }

    public function test_transaction_is_rejected_when_stock_is_insufficient(): void
    {
        $product = Product::create(['name' => 'Gula', 'code' => 'PRD-T2', 'stock' => 2, 'price' => 15000]);

        $this->actingAs($this->makeUser('staff'))->post('/transactions', [
            'product_id' => $product->id,
            'quantity' => 5,
        ])->assertSessionHas('error');

        $this->assertEquals(2, $product->fresh()->stock);
        $this->assertEquals(0, Sale::count());
    }

    public function test_product_with_sales_history_cannot_be_deleted(): void
    {
        $staff = $this->makeUser('staff');
        $product = Product::create(['name' => 'Air', 'code' => 'PRD-T3', 'stock' => 5, 'price' => 3000]);

        $this->actingAs($staff)->post('/transactions', ['product_id' => $product->id, 'quantity' => 1]);

        $this->actingAs($staff)->delete("/products/{$product->id}")->assertSessionHas('error');
        $this->assertNotNull(Product::find($product->id));
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)->delete("/users/{$admin->id}")->assertSessionHas('error');
        $this->assertNotNull(User::find($admin->id));
    }
}
