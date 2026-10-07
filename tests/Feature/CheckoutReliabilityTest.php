<?php

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->cashier = User::factory()->create();
    $this->cashier->syncRoles('cashier');
    $this->product = Product::create([
        'name' => 'Milk', 'code' => 'MILK-1', 'price' => 125.50,
        'product_qty' => 5, 'status' => 'available',
    ]);
    $this->payload = [
        'items' => [['product_id' => $this->product->id, 'qty' => 2, 'unit_price' => 0.01]],
        'discount_type' => 'fixed', 'payment_method' => 'cash', 'payment_status' => 'paid',
    ];
    $this->actingAs($this->cashier);
});

test('checkout uses stored price and records stock and sale together', function () {
    $this->postJson('/pos/sale', $this->payload)->assertOk()->assertJsonPath('grand_total', 251);
    $this->assertDatabaseHas('sale_items', ['product_id' => $this->product->id, 'unit_price' => 125.50, 'qty' => 2]);
    expect($this->product->fresh()->product_qty)->toBe(3);
});

test('duplicate product rows are rejected without changing stock', function () {
    $this->payload['items'][] = $this->payload['items'][0];
    $this->postJson('/pos/sale', $this->payload)->assertUnprocessable()->assertJsonValidationErrors('items.0.product_id');
    expect(Sale::count())->toBe(0);
    expect($this->product->fresh()->product_qty)->toBe(5);
});

test('insufficient stock rolls back the entire checkout', function () {
    $this->payload['items'][0]['qty'] = 6;
    $this->postJson('/pos/sale', $this->payload)->assertUnprocessable();
    expect(Sale::count())->toBe(0);
    expect($this->product->fresh()->product_qty)->toBe(5);
});

test('unavailable products cannot be sold', function () {
    $this->product->update(['status' => 'unavailable']);
    $this->postJson('/pos/sale', $this->payload)->assertUnprocessable();
    expect(Sale::count())->toBe(0);
});

test('new sales cannot start as refunded', function () {
    $this->payload['payment_status'] = 'refunded';
    $this->postJson('/pos/sale', $this->payload)->assertUnprocessable();
    expect(Sale::count())->toBe(0);
});

test('cashiers cannot read another cashiers sale or receipt', function () {
    $this->postJson('/pos/sale', $this->payload)->assertOk();
    $sale = Sale::firstOrFail();
    $otherCashier = User::factory()->create();
    $otherCashier->syncRoles('cashier');
    $this->actingAs($otherCashier);
    $this->getJson('/sales/'.$sale->id)->assertForbidden();
    $this->get('/pos/receipt/'.$sale->receipt_number)->assertForbidden();
});
