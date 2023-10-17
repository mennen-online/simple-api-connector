<?php

namespace MennenOnline\SimpleApiConnector\Configuration;

use Illuminate\Support\Arr;
use MennenOnline\SimpleApiConnector\Models\BaseResponseModel;

class ConfigurationLoader
{
    public static function loadConfiguration(string $configuration): Configuration
    {
        $configuration = config('api.'.$configuration);

        return new Configuration(
            Arr::get($configuration, 'fallback_response_model', BaseResponseModel::class),
            $configuration['base_url'],
            $configuration['authentication'],
            $configuration['endpoints'],
            $configuration['response_models'],
        );
    }
}
