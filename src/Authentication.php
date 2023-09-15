<?php

class Authentication
{
    private string $clientID;
    private string $clientSecret;
    private string $baseUrl;
    private string $authServer;
    private ?string $accessToken;
    private ?int $expires;

    public function __construct(string $clientID, string $clientSecret, string $baseUrl, string $authServer) {
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;
        $this->baseUrl = $baseUrl;
        $this->authServer = $authServer;

        $this->accessToken = null;
        $this->expires = null;
    }

    /**
     * @throws Exception
     */
    public function getAccessToken(): string {
        if ($this->accessToken == null || $this->expires < time()) {
            $this->generateToken();
        }
        return $this->accessToken;
    }

    /**
     * @throws Exception
     */
    private function generateToken(): void {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // do not print to CL

        $url = $this->authServer . '/oauth/token';
        curl_setopt($ch, CURLOPT_URL, $url);

        $body = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'audience' => $this->baseUrl
        ];
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));

        $headers = [
            'header' => 'Content-type: application/x-www-form-urlencoded',
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        if ($response === false) {
            curl_close($ch);
            throw new Exception('CURL Error: ' . curl_error($ch));
        }

        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $response = json_decode($response);
        if ($responseCode == 200) {
            $this->accessToken = $response->access_token;
            $this->expires = time() + $response->expires_in;
        } else {
            throw new Exception('Could not generate token: ' . $response->error . ' - ' . $response->error_description);
        }
    }
}