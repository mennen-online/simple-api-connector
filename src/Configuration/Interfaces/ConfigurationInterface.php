<?php

namespace MennenOnline\SimpleApiConnector\Configuration\Interfaces;

interface ConfigurationInterface
{
    public function getFallbackResponseModel(): string;

    public function getBaseUrl(): string;

    public function getAuthentication(): array;

    public function getEndpoints(): array;

    public function getResponseModels(): array;

    public function getEndpoint(string $endpoint): string;

    public function getResponseModel(string $endpoint): string;
}