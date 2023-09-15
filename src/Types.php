<?php

class ApiAuthenticationConfig {
    private string $authServer;
    private string $clientId;
    private string $clientSecret;

    public function __construct(array $authenticationConfig) {
        $this->authServer = $authenticationConfig['authServer'];
        $this->clientId = $authenticationConfig['clientId'];
        $this->clientSecret = $authenticationConfig['clientSecret'];
    }

    public function getAuthServer(): string {
        return $this->authServer;
    }

    public function getClientId(): string {
        return $this->clientId;
    }

    public function getClientSecret(): string {
        return $this->clientSecret;
    }
}

class ApiConfig {
    private string $baseUrl;
    private string $version;
    private string $workspace;
    private string $api;
    private string $config;
    private ?ApiAuthenticationConfig $apiAuthenticationConfig;

    /**
     * @throws Exception
     */
    function __construct(array $apiConfig) {
        if (!array_key_exists('workspace', $apiConfig)) {
            throw new Exception('A workspace needs to be defined');
        }
        if (!array_key_exists('api', $apiConfig)) {
            throw new Exception('An API endpoint needs to be defined');
        }

        $this->baseUrl = 'https://rest.spinque.com/';
        if (array_key_exists('baseUrl', $apiConfig)) {
            $this->baseUrl = $apiConfig['baseUrl'];
        }

        $this->version = '4';
        if (array_key_exists('version', $apiConfig)) {
            $this->version = $apiConfig['version'];
        }

        $this->workspace = $apiConfig['workspace'];
        $this->api = $apiConfig['api'];

        $this->config = 'default';
        if (array_key_exists('config', $apiConfig)) {
            $this->config = $apiConfig['config'];
        }

        $this->apiAuthenticationConfig = null;
        if (array_key_exists('authentication', $apiConfig)) {
            $this->apiAuthenticationConfig = new ApiAuthenticationConfig($apiConfig['authentication']);
        }
    }

    public function getApi(): ?string {
        return $this->api;
    }

    public function getAuthenticationConfig(): ?ApiAuthenticationConfig {
        return $this->apiAuthenticationConfig;
    }

    public function getBaseUrl(): string {
        return $this->baseUrl;
    }

    public function getConfig(): string {
        return $this->config;
    }

    public function getVersion(): string {
        return $this->version;
    }

    public function getWorkspace(): ?string {
        return $this->workspace;
    }
}

class Query {
    private string $endpoint;
    private ?array $parameters;

    public function __construct(string $endpoint, array $parameters) {
        $this->endpoint = $endpoint;
        $this->parameters = $parameters;
    }

    public function getEndpoint(): string {
        return $this->endpoint;
    }

    public function getParameters(): array {
        return $this->parameters;
    }
}

enum ResultType {
    case RESULTS;
    case STATISTICS;
}