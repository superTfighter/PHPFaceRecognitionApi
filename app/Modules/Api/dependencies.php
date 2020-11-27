<?php

// -----------------------------------------------------------------------------
// Actions - Repositories - Factories
// -----------------------------------------------------------------------------
$container['@Api\ApiAction'] = function($container) {
    return new \App\Modules\Api\Actions\ApiAction($container);
};

