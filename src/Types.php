<?php

class ApiAuthenticationConfig {
    private string $authServer;
    private string $clientId;
    private string $clientSecret;

    public function __construct($authenticationConfig) {
        $this->authServer = $authenticationConfig->authSever;
        $this->clientId = $authenticationConfig->clientId;
        $this->clientSecret = $authenticationConfig->clientSecret;
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
    private ?ApiAuthenticationConfig $authentication;

    /**
     * @throws Exception
     */
    function __construct($apiConfig) {
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
            $this->baseUrl = $apiConfig['config'];
        }

        $this->authentication = null;
        if (array_key_exists('authentication', $apiConfig)) {
            $this->authentication = new ApiAuthenticationConfig($apiConfig['authentication']);
        }
    }

    public function getApi(): ?string {
        return $this->api;
    }

    public function getAuthentication(): ?ApiAuthenticationConfig {
        return $this->authentication;
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
    private array $parameters;

    public function __construct($endpoint, $parameters) {
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

$x = new ApiConfig(
    [
        'workspace' => 'demo08',
        'api' => 'test'
    ]
);
echo $x->getBaseUrl();