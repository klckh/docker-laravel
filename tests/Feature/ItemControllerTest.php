<?php

namespace Tests\Feature;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Test normal create item succeeds
     *
     * @return void
     */
    public function test_create()
    {
        $item = [
            'sku' => 'TEST123',
            'name' => 'testing product',
            'qty' => 1,
        ];

        $response = $this
            ->actingAs($this->user)
            ->postJson('/api/items', $item);
        $response->assertStatus(200);
        $response->assertJson([
            'code' => Controller::CODE_SUCCESS,
            'body' => $item,
        ]);
    }

    /**
     * Test create item required fields validation
     *
     * @return void
     */
    public function test_create_required_fields()
    {
        $response = $this
            ->actingAs($this->user)
            ->postJson('/api/items', [
                'name' => 'invalid',
            ]);
        $response->assertStatus(422);
    }

    /**
     * Test create item integer qty field
     *
     * @return void
     */
    public function test_create_valid_qty()
    {
        $response = $this
            ->actingAs($this->user)
            ->postJson('/api/items', [
                'sku' => 'test',
                'name' => 'test',
                // qty should be integer
                'qty' => 'invalid',
            ]);
        $response->assertStatus(422);
    }

    /**
     * Test create item must have unique SKU
     *
     * @return void
     */
    public function test_create_unique()
    {
        $item = [
            'sku' => 'TEST123',
            'name' => 'testing product',
            'qty' => 1,
        ];

        Item::create($item);

        // Test that items same SKU will not pass validation
        $response = $this
            ->actingAs($this->user)
            ->postJson('/api/items', $item);
        $response->assertStatus(422);
    }

    /**
     * Test get existing item
     *
     * @return void
     */
    public function test_get_existing_item()
    {
        $item = [
            'sku' => 'TEST123',
            'name' => 'testing product',
            'qty' => 1,
        ];

        $model = Item::create($item);
        $response = $this
            ->actingAs($this->user)
            ->getJson("/api/items/{$model->getKey()}");

        $response->assertStatus(200);
        $response->assertJsonPath('body.sku', 'TEST123');
    }

    /**
     * Test get non existing item returns 404 Not Found
     *
     * @return void
     */
    public function test_get_non_existing_item()
    {
        $response = $this
            ->actingAs($this->user)
            ->getJson("/api/items/9999");

        $response->assertStatus(404);
    }

    /**
     * Test search items API
     *
     * @return void
     */
    public function test_search_items()
    {
        $response = $this
            ->actingAs($this->user)
            ->getJson("/api/items");

        $response->assertStatus(200);
    }

    /**
     * Test update item
     *
     * @return void
     */
    public function test_update_item()
    {
        $item = [
            'sku' => 'TEST123',
            'name' => 'testing product',
            'qty' => 1,
        ];

        $model = Item::create($item);

        $update = [
            'name' => 'changed',
            'qty' => 5,
        ];

        $response = $this
            ->actingAs($this->user)
            ->putJson("/api/items/{$model->getKey()}", $update);

        $expected = array_merge($item, $update);

        $response->assertStatus(200);
        $response->assertJson([
            'body' => $expected,
        ]);
    }

    /**
     * Test delete an existing item
     *
     * @return void
     */
    public function test_delete_item()
    {
        $item = [
            'sku' => 'TEST123',
            'name' => 'testing product',
            'qty' => 1,
        ];

        $model = Item::create($item);

        $response = $this
            ->actingAs($this->user)
            ->delete("/api/items/{$model->getKey()}");

        $response->assertStatus(200);
    }
}
