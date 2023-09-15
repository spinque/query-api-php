<?php

function pathFromQueries(array $queries) : string {
    $processed = array();
    foreach ($queries as $query) {
        $processed[] = pathFromQuery($query);
    }
    return joinPaths($processed);
}

function pathFromQuery(Query $query) : string {
    $parts = ["e", $query->getEndpoint()];
    if ($query->getParameters() != null) {
        foreach ($query->getParameters() as $parameter => $value) {
            array_push($parts, 'p', rawurlencode($parameter), rawurlencode($value));
        }
    }
    return joinPaths($parts);
}

/**
 * @throws Exception
 */
function urlFromQueries(ApiConfig $apiConfig, mixed $queries, ResultType $resultType) : string {
    if (gettype($queries) != 'array') {
        $queries = array($queries);
    }
    if (!$apiConfig->getBaseUrl()) {
        throw new Exception('Base URL missing.');
    }
    if (!$apiConfig->getVersion()) {
        throw new Exception('Version missing.');
    }
    if (!$apiConfig->getWorkspace()) {
        throw new Exception('Workspace missing.');
    }
    if (!$apiConfig->getApi()) {
        throw new Exception('API name missing.');
    }

    # Construct base URL containing Spinque version and workspace
    $url = $apiConfig->getBaseUrl();
    if (!str_ends_with($url, '/')) {
        $url = $url . '/';
    }
    $url = $url.joinPaths([$apiConfig->getVersion(), $apiConfig->getWorkspace(), 'api', $apiConfig->getApi()]);

    # Add the path represented by the Query objects and request type
    $resultType = $resultType == ResultType::RESULTS ? 'results' : 'statistics';
    $url = $url . '/' . joinPaths([pathFromQueries($queries), $resultType]);

    # Add config if provided
    if ($apiConfig->getConfig()) {
        $url = $url . '?config=' . $apiConfig->getConfig();
    }

    return $url;
}

function joinPaths(array $segments) : string {
    $newSegments = array();
    $i = 0;
    foreach ($segments as $segment) {
        if ($i > 0) {
            $segment = trim($segment, '/');
        } else {
            $segment = rtrim($segment, '/');
        }
        $newSegments[] = $segment;
        $i++;
    }
    $segments = $newSegments;

    $resultArray = array();
    foreach ($segments as $segment) {
        $subSegments = explode('/', $segment);
        foreach ($subSegments as $s) {
            if ($s == '.' or $s == '') {
                continue;
            } else if ($s == '..') {
                array_pop($resultArray);
            } else {
                $resultArray[] = $s;
            }
        }
    }
    return implode('/', $resultArray);
}