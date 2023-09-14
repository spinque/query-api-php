<?php

class Api {
    public ApiConfig $apiConfig;

    /**
     * @throws Exception
     */
    public function __construct($apiConfig) {
        $this->apiConfig = new ApiConfig($apiConfig);
    }
}