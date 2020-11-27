<?php

use ALFI\Config;
use ALFI\Api\IIRApi;
use ALFI\Api\GeneralApi;

// API (core library)
$container['api'] = function ($container) {
    $settings = $container->get('settings')['settings']['System']['Api'];

    $api = new stdClass;

    // Load Api settings
    foreach ($settings as $key => $value)
    {
        // Set Class
        $class = '\\ALFI\Api\\'. $value['class'];

        // User Agent required
        $settings = ['headers' => ['User-Agent' => $value['user-agent']]];

        $api->{$key} = new $class(new Config($value['options']), $settings);
    }

    return $api;
};
