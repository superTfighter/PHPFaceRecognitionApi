<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->group('/', function () use ($container) {

    // CRON indító route
    $this->get   ('cron', '@System\Cron\MainAction:index')->setName('cron');

});