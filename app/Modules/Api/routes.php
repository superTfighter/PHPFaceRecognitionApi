<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get   ('/test',                                 '@Api\ApiAction:test' );

$app->post('/store/{id}',                       '@Api\ApiAction:store');

$app->get('/train',                                   '@Api\ApiAction:train');
$app->post('/recognize',                              '@Api\ApiAction:recognize');

$app->delete('/delete/temp',                             '@Api\ApiAction:deleteTempFolder');
$app->delete('/delete/all',                              '@Api\ApiAction:deleteAllData');