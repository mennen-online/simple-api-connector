# Simple Api Connector
## Description
Are you tired of writing new API Connectors following the same procedures?

Save time using this Package:

Now you are able to connect third party APIs in a simple way: Through Laravels config files.

## Installation
```bash 
composer require mennen-online/simple-api-connector
```

## Usage
Create a new config file in config/api directory, named for example my-shopware6-shop.php
Paste the following content into the file:
```php
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
        'users/:id' => '/users/:id',
        'users/:user_id/orders/:order_id' => '/users/:user_id/orders/:order_id
    ],
    'response_models' => [
        'categories' => 'Model::class', //Response model for Categories
        'products' => 'Model::class', //Response model for Products
    ]
```

### Now we will explore the single Array keys and their meaning:
* fallback_response_model => We use this model if no response model is defined for a specific endpoint
* base_url => The base url of the API
* authentication.type => The authentication method used for the API
* authentication.username => The username used for basic and digest authentication
* authentication.password => The password used for basic and digest authentication
* authentication.token => The token used for bearer and token authentication
* authentication.client_id => The client id used for client_credentials authentication
* authentication.client_secret => The client secret used for client_credentials authentication
* authentication.authentication_url => The authentication url used for client_credentials authentication
* endpoints => The endpoints of the API as endpoint_name => endpoint_url
* response_models => The response models of the API as endpoint_name => response_model

The Package allows GET, POST, PUT and DELETE requests.

To instanciate the API Connector use the following code:
```php
$connector = new \MennenOnline\SimpleApiConnector\Connector\ApiConnector('my-shopware6-shop');

// To Call the API use the following code:

// GET
$response = $connector->get('categories');

// POST
$response = $connector->post('categories', ['name' => 'My Category']);

// PUT
$response = $connector->put('categories/1', ['name' => 'My Category']);

// DELETE
$response = $connector->delete('categories/1');


// To use URLs with names ID
$response = $connector->get('users/:id', [':id' => 1]);

// To use URLs with multiple names ID
$response = $connector->get('users/:user_id/orders/:order_id', [':user_id' => 1, ':order_id' => 1]);
```

```