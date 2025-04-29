<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test get with authorization
     *
     * @return void
     */
    public function test_get_products_with_token()
    {

        $user = User::factory()->create(['email' => 'carlos@gmail.com', 'name' => 'carlos', 'roles' => 'admin', 'password' => Hash::make('1223')]);
        $response = $this->actingAs($user, 'api')->get('api/v1/products');
        $data = json_decode($response->getContent());

        $this->assertEquals(200, $response->status());
        $this->assertEquals('Products retrieved successfully', $data->message, 'È  igual');

    }

    /**
     * Test Create product with authorization
     *
     * @return void
     */
    public function test_create_product_with_expected_data()
    {
        $user = User::factory()->create(['email' => 'carlos@gmail.com', 'name' => 'carlos', 'roles' => 'admin', 'password' => bcrypt('1223')]);
        $category = Category::factory()->create(['name' => 'Anything', 'description' => 'Anything']);
        $Product = [
            'name' => 'Anything',
            'description' => 'Anything',
            'price' => 30.0,
            'sku' => 'Anything',
            'quantity' => 1,
            'category_id' => $category->id,
        ];
        $response = $this->actingAs($user, 'api')->post('/api/v1/products', $Product);
        $data = json_decode($response->getContent());
        $this->assertEquals(201, $response->status());
        $this->assertEquals('Product created successfully', $data->message, 'È  igual');

    }

    public function test_create_product_with_unexpected_data()
    {
        $user = User::factory()->create(['email' => 'carlos@gmail.com', 'name' => 'carlos', 'roles' => 'admin', 'password' => bcrypt('1223')]);
        $Product = [
            'title' => 'Anything',
            'type' => 'Anything',
            'description' => 'Anything',
        ];
        $this->actingAs($user, 'api')->post('/api/v1/products', $Product)->assertStatus(302);

    }

    public function test_update_product_with_expected_data()
    {
        $user = User::factory()->create(['email' => 'carlos@gmail.com', 'name' => 'carlos', 'roles' => 'admin', 'password' => bcrypt('1223')]);

        $category = Category::factory()->create(['name' => 'Anything', 'description' => 'Anything']);
        $Product = [
            'name' => 'Anything',
            'description' => 'Anything',
            'price' => 30.0,
            'sku' => 'Anything',
            'quantity' => 1,
            'category_id' => $category->id,
        ];
        Product::factory()->create($Product);
        $ProductUpdated = [
            'name' => 'NotAnything',
            'quantity' => 2,
            'price' => 31.0,
        ];
        $response = $this->actingAs($user, 'api')->put('/api/v1/products/1', $ProductUpdated);
        $response->assertStatus(200);


    }

    public function test_update_product_with_unexpected_data()
    {
        $user = User::factory()->create(['email' => 'carlos@gmail.com', 'name' => 'carlos', 'roles' => 'admin', 'password' => bcrypt('1223')]);
        $category = Category::factory()->create(['name' => 'Anything', 'description' => 'Anything']);
        $Product = [
            'name' => 'Anything',
            'description' => 'Anything',
            'price' => 30.0,
            'sku' => 'Anything',
            'quantity' => 1,
            'category_id' => $category->id,
        ];
        Product::factory()->create($Product);
        $ProductUpdated = [
            'name' => 'Anything',
            'description' => 'NotAnything',
            'price' => '31.0',
            'sku' => 'Anything',
        ];
        $this->actingAs($user, 'api')->put('/api/v1/products/1', $ProductUpdated)->assertStatus(302);


    }

    public function test_find_product_with_existing_id()
    {
        $user = User::factory()->create(attributes: ['email' => 'carlos@gmail.com', 'name' => 'carlos', 'roles' => 'admin', 'password' => Hash::make('1223')]);
        $category = Category::factory()->create(['name' => 'Anything', 'description' => 'Anything']);
        $Product = [
            'name' => 'Anything',
            'description' => 'Anything',
            'price' => 30.0,
            'sku' => 'Anything',
            'quantity' => 1,
            'category_id' => $category->id,
        ];
        Product::factory()->create($Product);
        $this->actingAs($user, 'api')->get('/api/v1/products/1')->assertOk()->assertSee($Product);
    }

    public function test_find_product_with_not_existing_id()
    {
        $user = User::factory()->create(attributes: ['email' => 'carlos@gmail.com', 'name' => 'carlos', 'roles' => 'admin', 'password' => Hash::make('1223')]);
        $this->actingAs($user, 'api')->get('/api/v1/products/1')->assertStatus(500);

    }

    public function test_delete_product_with_existing_id()
    {
        $user = User::factory()->create(['email' => 'carlos@gmail.com', 'name' => 'carlos', 'roles' => 'admin', 'password' => Hash::make('1223')]);
        $category = Category::factory()->create(['name' => 'Anything', 'description' => 'Anything']);
        $Product = [
            'name' => 'Anything',
            'description' => 'Anything',
            'price' => 30.0,
            'sku' => 'Anything',
            'quantity' => 1,
            'category_id' => $category->id,
        ];
        Product::factory()->create($Product);
        $this->actingAs($user, 'api')->delete('api/v1/products/1')->assertOk();
        $this->actingAs($user, 'api')->get('api/v1/products/1')->assertStatus(500);
    }

    public function test_delete_product_with_not_existing_id()
    {
        $user = User::factory()->create(['email' => 'carlos@gmail.com', 'name' => 'carlos', 'roles' => 'admin', 'password' => Hash::make('1223')]);
        $this->actingAs($user, 'api')->delete('api/v1/products/1')->assertStatus(500);
    }
}
