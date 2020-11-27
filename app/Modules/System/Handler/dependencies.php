<?php

// logolni minden exceptiont amit amugy csak a slim kezelne le logolas nelkul
$container['errorHandler'] = function ($container) {
    return new App\Modules\System\Handler\MonologError($container);
};

$container['phpErrorHandler'] = function ($container) {
    return new App\Modules\System\Handler\MonologError($container);
};

// notice, warning, mindent(!) exceptionne
set_error_handler(function ($severity, $message, $file, $line) {
    // error squelch operator support, ha ott a @ akkor ignore
    if ( error_reporting() == 0 )
        return true;

    throw new \ErrorException($message, 0, $severity, $file, $line);
});

// Monolog
$container['logger'] = function ($c) {
    $logger = new Monolog\Logger($this->settings['settings']['System']['Logger']['default']['name']);

    // APP Metadata
    $logger->pushProcessor(new Monolog\Processor\PsrLogMessageProcessor());
    $logger->pushProcessor(new Monolog\Processor\IntrospectionProcessor());
    $logger->pushProcessor(new Monolog\Processor\WebProcessor());
    $logger->pushProcessor(new Monolog\Processor\ProcessIdProcessor());
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());

    // CLI notify off
    if (PHP_SAPI !== 'cli') {
      $logger->pushHandler(new Monolog\Handler\StreamHandler($this->settings['settings']['System']['Logger']['default']['path'], Monolog\Logger::DEBUG));
    } else {
      $logger->pushHandler(new Monolog\Handler\StreamHandler($this->settings['settings']['System']['Logger']['default']['path'], Monolog\Logger::NOTICE));
    }

    return $logger;
};
