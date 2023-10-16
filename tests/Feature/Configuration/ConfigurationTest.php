<?php

namespace MennenOnline\SimpleApiConnector\Tests\Feature\Configuration;

use Illuminate\Support\Facades\Config;
use MennenOnline\LaravelResponseModels\Models\BaseModel;
use MennenOnline\SimpleApiConnector\Configuration\Configuration;
use MennenOnline\SimpleApiConnector\Configuration\ConfigurationLoader;
use MennenOnline\SimpleApiConnector\Configuration\Interfaces\ConfigurationInterface;
use MennenOnline\SimpleApiConnector\Tests\TestCase;

class ConfigurationTest extends TestCase
{
    private ?Configuration $configuration;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('api.example_api', [
            'fallback_response_model' => \MennenOnline\LaravelResponseModels\Models\BaseModel::class,
            'base_url' => 'https://example.com/api',
            'authentication' => [
                'type' => 'basic', // basic, bearer, token, digest
                'username' => 'my-username', //use for basic
                'password' => 'my-password', //use for basic
                'token' => '', //use for bearer and token
            ],
            'endpoints' => [
                'categories' => '/categories',
                'products' => '/products',
            ],
            'response_models' => [
                'categories' => 'Category::class', //Response model for Categories
                'products' => 'Product::class', //Response model for Products
            ]]);

        $this->configuration = ConfigurationLoader::loadConfiguration('example_api');
    }

    public function test_configuration_load_can_load_expected_configuration_from_config_files(): void
    {
        $this->assertInstanceOf(\MennenOnline\SimpleApiConnector\Configuration\Configuration::class, $this->configuration);

        $interfaces = class_implements($this->configuration);

        $this->assertArrayHasKey(ConfigurationInterface::class, $interfaces);
    }

    public function test_the_configuration_object_returns_the_expected_fallback_response_model(): void
    {
        $this->assertSame(BaseModel::class, $this->configuration->getFallbackResponseModel());
    }

    public function test_the_configuration_object_returns_the_expected_base_url(): void
    {
        $this->assertSame('https://example.com/api', $this->configuration->getBaseUrl());
    }

    public function test_the_configuration_object_returns_the_expected_authentication_values(): void
    {
        $this->assertSame('basic', $this->configuration->getAuthentication()['type']);

        $this->assertSame('my-username', $this->configuration->getAuthentication()['username']);

        $this->assertSame('my-password', $this->configuration->getAuthentication()['password']);

        $this->assertSame('', $this->configuration->getAuthentication()['token']);
    }

    public function test_the_configuration_object_returns_the_expected_endpoints(): void
    {
        $this->assertArrayHasKey('categories', $this->configuration->getEndpoints());

        $this->assertArrayHasKey('products', $this->configuration->getEndpoints());

        $this->assertSame('/categories', $this->configuration->getEndpoints()['categories']);

        $this->assertSame('/products', $this->configuration->getEndpoints()['products']);
    }

    public function test_the_configuration_object_returns_the_expected_response_models(): void
    {
        $this->assertArrayHasKey('categories', $this->configuration->getResponseModels());

        $this->assertArrayHasKey('products', $this->configuration->getResponseModels());

        $this->assertSame('Category::class', $this->configuration->getResponseModels()['categories']);

        $this->assertSame('Product::class', $this->configuration->getResponseModels()['products']);
    }
}
