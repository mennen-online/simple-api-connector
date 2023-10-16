<?php

namespace MennenOnline\SimpleApiConnector\Tests\Unit\Configuration;

use Illuminate\Support\Facades\Config;
use MennenOnline\SimpleApiConnector\Tests\TestCase;

class ApiConfigurationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('api.example_api', [
            'base_url' => 'https://api.holofy.nl',
            'authentication' => [
                'type' => 'basic',
                'username' => 'holofy',
                'password' => 'holofy',
            ],
            'endpoints' => [
                'basket' => '/basket',
            ],
            'response_models' => [
                'basket' => \MennenOnline\SimpleApiConnector\Tests\Stubs\Basket::class,
            ],
        ]);
    }

    public function test_it_can_load_the_configuration_file()
    {
        $this->assertIsArray(config('api'));

        $this->assertArrayHasKey('example_api', config('api'));
    }

    public function test_it_can_load_the_configuration_file_with_a_specific_api()
    {
        $this->assertIsArray(config('api.example_api'));
    }

    public function test_it_can_load_the_configuration_file_with_a_specific_api_and_a_specific_endpoint()
    {
        $this->assertSame('/basket', config('api.example_api.endpoints.basket'));
    }

    public function test_it_can_load_the_configuration_file_with_a_specific_api_and_a_specific_endpoint_and_a_specific_model(
    ) {
        $this->assertEquals('MennenOnline\SimpleApiConnector\Tests\Stubs\Basket',
            config('api.example_api.response_models.basket'));
    }
}
