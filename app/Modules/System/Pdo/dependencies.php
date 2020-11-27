<?php

// Pdo
$container['pdo'] = function ($container) {
    $settings = $container->get('settings')['settings']['System']['Pdo'];

    $pdo = new stdClass;

    // Load Pdo settings
    foreach ($settings as $key => $value) {
        $pdo->{$key} = new \FaaPz\PDO\Database($value['dsn'], $value['username'], $value['password'], $value['pdo']);
    }

    return $pdo;
};
