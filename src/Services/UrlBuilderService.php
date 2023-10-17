<?php

namespace MennenOnline\SimpleApiConnector\Services;

use MennenOnline\SimpleApiConnector\Configuration\Configuration;

class UrlBuilderService
{
    public function __construct(
        private Configuration $configuration
    ) {
    }

    public function getEndpoint(string $endpoint, array|int|string $resourceIds = null): string
    {
        if ($resourceIds) {
            $finalEndpoint = str($this->configuration->getEndpoint($endpoint));
            if (is_array($resourceIds)) {
                foreach ($resourceIds as $resourceKey => $resourceId) {
                    $finalEndpoint = $finalEndpoint->replace($resourceKey, $resourceId);
                }

                return $finalEndpoint->toString();
            } else {
                return $finalEndpoint->append('/'.$resourceIds)->toString();
            }
        }

        return $this->configuration->getEndpoint($endpoint);
    }
}
