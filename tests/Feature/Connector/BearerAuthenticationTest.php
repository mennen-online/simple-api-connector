<?php

namespace Feature\Connector;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use MennenOnline\SimpleApiConnector\Connector\ApiConnector;
use MennenOnline\SimpleApiConnector\Models\BaseResponseModel;
use MennenOnline\SimpleApiConnector\Tests\TestCase;

class BearerAuthenticationTest extends TestCase
{
    protected ApiConnector $apiConnector;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('api.example_api', [
            'fallback_response_model' => BaseResponseModel::class,
            'base_url' => 'https://example.com/api',
            'authentication' => [
                'type' => 'bearer', // basic, bearer, token, digest, client_credentials
                'username' => '', //use for basic and digest
                'password' => '', //use for basic and digest
                'token' => 'my-token', //use for bearer and token
                'client_id' => '', //use for client_credentials
                'client_secret' => '', //use for client_credentials
                'authentication_url' => '', //use for client_credentials
            ],
            'endpoints' => [
                'categories' => '/categories',
                'products' => '/products',
            ],
            'response_models' => [
                'categories' => 'Category::class', //Response model for Categories
                'products' => 'Product::class', //Response model for Products
            ],
        ]);
    }

    public function test_it_can_make_a_get_request(): void
    {
        Http::fake([
            'https://example.com/api/categories' => Http::response([
                'data' => [
                    [
                        'id' => 1,
                        'name' => 'Category 1',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Category 2',
                    ],
                ],
            ]),
        ]);

        $this->apiConnector = new ApiConnector('example_api');

        $response = $this->apiConnector->get('categories');

        $this->assertIsArray($response->data);

        $this->assertArrayHasKey('id', $response->data[0]);

        $this->assertArrayHasKey('name', $response->data[0]);
    }

    public function test_it_can_make_a_post_request(): void
    {
        Http::fake([
            'https://example.com/api/categories' => Http::response([
                'data' => [
                    'id' => 3,
                    'name' => 'Category 3',
                ],
            ]),
        ]);

        $this->apiConnector = new ApiConnector('example_api');

        $response = $this->apiConnector->post('categories', [
            'name' => 'Category 3',
        ]);

        $this->assertIsArray($response->data);

        $this->assertArrayHasKey('id', $response->data);

        $this->assertArrayHasKey('name', $response->data);

        $this->assertEquals('Category 3', $response->data['name']);
    }

    public function test_it_can_make_a_put_request(): void
    {
        Http::fake([
            'https://example.com/api/categories/3' => Http::response([
                'data' => [
                    'id' => 3,
                    'name' => 'New Category 3',
                ],
            ]),
        ]);

        $this->apiConnector = new ApiConnector('example_api');

        $response = $this->apiConnector->put('categories', 3, [
            'name' => 'New Category 3',
        ]);

        $this->assertIsArray($response->data);

        $this->assertArrayHasKey('id', $response->data);

        $this->assertArrayHasKey('name', $response->data);

        $this->assertEquals('New Category 3', $response->data['name']);
    }

    public function test_it_can_make_delete_requests(): void
    {
        Http::fake([
            'https://example.com/api/categories/3' => Http::response([
                'data' => [
                    'id' => 3,
                    'name' => 'New Category 3',
                ],
            ]),
        ]);

        $this->apiConnector = new ApiConnector('example_api');

        $response = $this->apiConnector->delete('categories', 3);

        $this->assertIsArray($response->data);

        $this->assertArrayHasKey('id', $response->data);

        $this->assertArrayHasKey('name', $response->data);

        $this->assertEquals('New Category 3', $response->data['name']);
    }
}
