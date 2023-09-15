<?php

include('Types.php');
include('Authentication.php');
include('Utils.php');

class Api {
    public ApiConfig $apiConfig;

    public ?Authentication $authentication;

    /**
     * @throws Exception
     */
    public function __construct(array $apiConfig) {
        $this->apiConfig = new ApiConfig($apiConfig);
        $authenticationConfig = $this->apiConfig->getAuthenticationConfig();
        if ($authenticationConfig != null) {
            $this->authentication = new Authentication(
                $authenticationConfig->getClientId(),
                $authenticationConfig->getClientSecret(),
                $this->apiConfig->getBaseUrl(),
                $authenticationConfig->getAuthServer()
            );
        }
    }

    /**
     * @throws Exception
     */
    public function fetch(array $queries, array $options = null, ResultType $resultType = ResultType::RESULTS): string {
        $headers = ['Content-Type: application/json'];
        if ($this->authentication) {
            $token = $this->authentication->getAccessToken();
            $headers[] = 'Authorization: Bearer ' . $token;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // do not print to CL

        $url = urlFromQueries($this->apiConfig, $queries, $resultType);
        if ($options != null) {
            $options = http_build_query($options);
            curl_setopt($ch, CURLOPT_URL, $url . '?' . $options);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
        }

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception('CURL Error: ' . curl_error($ch));
        }
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return $this->responseHandler($response, $responseCode);
    }

    /**
     * @throws Exception
     */
    private function responseHandler(string $response, int $responseCode) : string {
        if ($responseCode == 200) {
            return $response;
        } else if ($responseCode == 401) {
            throw new Exception('Unauthorized error: ' . $response, 401);
        } else if ($responseCode == 400) {
            if (str_starts_with($response, 'no endpoint')) {
                throw new Exception('Endpoint not found: ' . $response, 400);
            }
            throw new Exception('Unauthorized error: ' . $response, 400);
        } else if ($responseCode == 500) {
            throw new Exception('Server error: ' . $response , 500);
        } else {
            throw new Exception('Unknown error: ' . $response, $responseCode);
        }
    }
}