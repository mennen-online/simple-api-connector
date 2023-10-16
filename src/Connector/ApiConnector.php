<?php

namespace MennenOnline\SimpleApiConnector\Connector;

use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use MennenOnline\SimpleApiConnector\Configuration\Configuration;
use MennenOnline\SimpleApiConnector\Models\BaseResponseModel;

class ApiConnector
{
    public function __construct(
        private readonly string $configuredApi,
        private string $responseModel = BaseResponseModel::class,
        private ?PendingRequest $request = null,
        private ?Configuration $configuration = null,
        private ?Carbon $tokenExpiresAt = null,
    ) {
        $this->configuration = $this->configuration ?? Configuration::loadConfiguration($this->configuredApi);

        $configuredEndpoints = config('simple-api-connector.'.$this->configuredApi);
        if ($configuredEndpoints) {
            return;
        }
        $this->request = Http::baseUrl($this->configuration->getBaseUrl());

        $authentication = $this->configuration->getAuthentication();

        $this->request = match ($authentication['type']) {
            'basic' => $this->request->withBasicAuth($authentication['username'], $authentication['password']),
            'digest' => $this->request->withDigestAuth($authentication['username'], $authentication['password']),
            'token' => $this->request->withToken($authentication['token']),
            'client_credentials' => $this->useClientCredentialsAuthentication($authentication),
            default => $this->request,
        };
    }

    public function get(string $endpoint, array $parameters = []): BaseResponseModel
    {
        $this->refreshToken();
        $this->loadResponseModel($endpoint);

        return new $this->responseModel($this->request->get($this->configuration->getEndpoint($endpoint), $parameters)->object() ?? []);
    }

    public function post(string $endpoint, array $parameters = []): BaseResponseModel
    {
        $this->refreshToken();
        $this->loadResponseModel($endpoint);

        return new $this->responseModel($this->request->post($this->configuration->getEndpoint($endpoint), $parameters)->object() ?? []);
    }

    public function put(string $endpoint, int|string $resourceId, array $parameters = []): BaseResponseModel
    {
        $this->refreshToken();
        $this->loadResponseModel($endpoint);

        return new $this->responseModel($this->request->put($this->configuration->getEndpoint($endpoint).'/'.$resourceId, $parameters)->object() ?? []);
    }

    public function delete(string $endpoint, int|string $resourceId, array $parameters = []): BaseResponseModel
    {
        $this->refreshToken();
        $this->loadResponseModel($endpoint);

        return new $this->responseModel($this->request->delete($this->configuration->getEndpoint($endpoint).'/'.$resourceId, $parameters)->object());
    }

    private function loadResponseModel(string $endpoint): void
    {
        if (($model = Arr::get($this->configuration->getResponseModels(), $endpoint)) && class_exists($model)) {
            $this->responseModel = $model;
        }
    }

    private function useClientCredentialsAuthentication(array $authentication): PendingRequest
    {
        $authenticationResponse = $this->request->post($authentication['authentication_url'], [
            'grant_type' => 'client_credentials',
            'client_id' => $authentication['client_id'],
            'client_secret' => $authentication['client_secret'],
        ])->object();

        $this->tokenExpiresAt = Carbon::now()->addSeconds($authenticationResponse->expires_in);

        $this->request->withToken($authenticationResponse->access_token);

        return $this->request;
    }

    private function refreshToken(): void
    {
        if ($this->tokenExpiresAt === null || $this->tokenExpiresAt->isFuture()) {
            return;
        }

        $this->request = $this->useClientCredentialsAuthentication($this->configuration->getAuthentication());
    }
}
