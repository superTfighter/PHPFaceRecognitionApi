<?php

// PREDIS
$container['predis'] = function ($container) {
    $settings = $container->get('settings')['settings']['System']['Predis'];

    $predisVar = [
        'prefix' => $settings['prefix']
    ];

    if ( isset($settings['password']) && $settings['password'] != '') {
        $predisVar['parameters']['password'] = $settings['password'];
    }

    return new Predis\Client(
                                [
                                    'host'   => $settings['host'],
                                    'port'   => $settings['port']
                                ],
                                $predisVar
                            );
};
