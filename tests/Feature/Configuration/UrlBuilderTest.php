<?php

namespace MennenOnline\SimpleApiConnector\Tests\Feature\Configuration;

use Illuminate\Support\Facades\Config;
use MennenOnline\SimpleApiConnector\Configuration\ConfigurationLoader;
use MennenOnline\SimpleApiConnector\Services\UrlBuilderService;
use MennenOnline\SimpleApiConnector\Tests\TestCase;

class UrlBuilderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('api.example_api', [
            'base_url' => 'https://example.com/api',
            'authentication' => [
                '',
            ],
            'endpoints' => [
                'users' => '/users',
                'users/:id/posts' => '/users/:id/posts',
                'users/:id/posts/:post_id/comments' => '/users/:id/posts/:post_id/comments',
            ],
            'response_models' => [
                'users' => 'App\Models\User',
                'posts' => 'App\Models\Post',
            ],
        ]);
    }

    public function test_a_simple_url_can_be_build(): void
    {
        $urlBuilder = new UrlBuilderService(ConfigurationLoader::loadConfiguration('example_api'));

        $this->assertEquals('/users', $urlBuilder->getEndpoint('users'));
    }

    public function test_build_a_url_with_fixed_directory_after_the_id(): void
    {
        $urlBuilder = new UrlBuilderService(ConfigurationLoader::loadConfiguration('example_api'));

        $this->assertEquals('/users/1/posts', $urlBuilder->getEndpoint('users/:id/posts', [':id' => 1]));
    }

    public function test_build_a_url_with_multiple_id_placeholders(): void
    {
        $urlBuilder = new UrlBuilderService(ConfigurationLoader::loadConfiguration('example_api'));

        $this->assertEquals('/users/1/posts/2/comments', $urlBuilder->getEndpoint('users/:id/posts/:post_id/comments', [':id' => 1, ':post_id' => 2]));
    }
}
