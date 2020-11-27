<?php

use ALFI\Config;

// PHP Cron Scheduler
$container['cron'] = function ($container) {
  return new \GO\Scheduler();
};

// -----------------------------------------------------------------------------
// Actions - Repositories - Factories
// -----------------------------------------------------------------------------
$container['@System\Cron\MainAction'] = function($container) {
    return new \App\Modules\System\Cron\Actions\MainAction($container);
};