<?php

include('../src/Api.php');

$api = new Api(
    array(
        'workspace' => 'course-chris',
        'config' => 'default',
        'api' => 'movies',
        'authentication' => array(
            'authServer' => 'https://login.spinque.com',
            'clientId' => 'bHul5K4nNCp0MEAcMzGtl8eBCnFL9FTz',
            'clientSecret' => 'BS1K4LQhgOd0i4CEs8ngjs9_59RZFzWihJAhQ3PVx-KtvHnrBvwm7PC7L83U5Duf',
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
