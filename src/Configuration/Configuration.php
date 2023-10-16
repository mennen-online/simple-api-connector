<?php

namespace MennenOnline\SimpleApiConnector\Configuration;

use Illuminate\Support\Arr;
use MennenOnline\LaravelResponseModels\Models\BaseModel;
use MennenOnline\SimpleApiConnector\Configuration\Interfaces\ConfigurationInterface;

class Configuration extends ConfigurationLoader implements ConfigurationInterface
{
    public function __construct(
        protected string $fallbackResponseModel,
        protected string $baseUrl,
        protected array $authentication,
        protected array $endpoints,
        protected array $responseModels,
    ) {
    }

    public function getFallbackResponseModel(): string
    {
        return $this->fallbackResponseModel;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getAuthentication(): array
    {
        return $this->authentication;
    }

    public function getEndpoints(): array
    {
        return $this->endpoints;
    }

    public function getResponseModels(): array
    {
        return $this->responseModels;
    }

    public function getEndpoint(string $endpoint): string
    {
        return Arr::get($this->endpoints, $endpoint);
    }

    public function getResponseModel(string $endpoint): string
    {
        return Arr::get($this->responseModels, $endpoint, BaseModel::class);
    }
}
