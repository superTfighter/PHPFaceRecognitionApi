<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get   ('/',                                     '@Api\ApiAction:test' );

$app->post('/store/{username}',                       '@Api\ApiAction:store');

$app->get('/train',                                   '@Api\ApiAction:train');
$app->post('/recognize',                              '@Api\ApiAction:recognize');