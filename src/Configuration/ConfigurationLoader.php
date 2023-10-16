<?php

namespace MennenOnline\SimpleApiConnector\Configuration;

class ConfigurationLoader
{
    public static function loadConfiguration(string $configuration): Configuration
    {
        $configuration = config('api.'.$configuration);

        return new Configuration(
            $configuration['fallback_response_model'],
            $configuration['base_url'],
            $configuration['authentication'],
            $configuration['endpoints'],
            $configuration['response_models'],
        );
    }
}
