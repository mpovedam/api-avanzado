<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_index()
    {
        Product::factory(5)->create();


        $response = $this->getJson('/api/v1/products');

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonCount(5);
    }

    public function test_create_new_product()
    {
        $data = [
            'name' => 'Gaseosa',
            'price' => 5000
        ];

        $response = $this->postJson('/api/v1/products', $data);

        $response->assertSuccessful();
        $response->assertHeader('content/type', 'application/json');
        $this->assertDatabaseHas('products', $data);
    }

    public function test_update_product()
    {
        $product = Product::factory()->create();

        $data = [
            'name' => 'Agua en botella',
            'price' => 2500
        ];

        $response = $this->patchJson("/api/v1/products/{$product->getKey()}", $data);
        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
    }

    public function test_show_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/v1/products/{$product->getKey()}");
        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
    }

    public function test_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/v1/products/{$product->getKey()}");
        $response->assertHeader('content-type', 'application/json');
        $this->assertSoftDeleted($product);
    }
}
