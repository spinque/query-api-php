<?php

include('../src/Api.php');

$api = new Api(
    array(
        'workspace' => 'course-chris',
        'config' => 'default',
        'api' => 'movies',
        'authentication' => array(
            'authServer' => 'https://login.spinque.com',
            'clientId' => '<CLIENT-ID>',
            'clientSecret' => '<CLIENT-TOKEN>',
        )
    )
);

$queries = [
    new Query('movie_search', array('query' => 'Keanu Reeves'))
];

try {
    echo $api->fetch($queries);
} catch (Exception $e) {
    echo $e;
}
