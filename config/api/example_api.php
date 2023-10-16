<?php

return [
    'fallback_response_model' => \MennenOnline\SimpleApiConnector\Models\BaseResponseModel::class,
    'base_url' => 'https://example.com/api',
    'authentication' => [
        'type' => 'basic', // basic, bearer, token, digest, client_credentials
        'username' => '', //use for basic and digest
        'password' => '', //use for basic and digest
        'token' => '', //use for bearer and token
        'client_id' => '', //use for client_credentials
        'client_secret' => '', //use for client_credentials
        'authentication_url' => '' //use for client_credentials
    ],
    'endpoints' => [
        'categories' => '/categories',
        'products' => '/products',
    ],
    'response_models' => [
        'categories' => 'Model::class', //Response model for Categories
        'products' => 'Model::class', //Response model for Products
    ]
];