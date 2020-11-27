<?php

define('APP_DIR', __DIR__);

date_default_timezone_set(date_default_timezone_get());
$locale = "hu_HU.utf8";

putenv("LC_ALL=$locale");
setlocale(LC_ALL, $locale);
bindtextdomain('messages', sprintf('%s/Resources/locale', __DIR__ ));
textdomain('messages');

// Load vendors1
require __DIR__.'/../vendor/autoload.php';

// Load application settings
$settings_path = __DIR__.'/settings.php';

if (file_exists($settings_path)) {
    $settings = require $settings_path;
} else {
    $settings = require __DIR__.'/settings.default.php';
}

// Check and load modules settings
$modules = $settings['settings']['modules'];

// Sort module by weight
array_multisort(array_column($modules, 'weight'), $modules);

$app = new \Slim\App($settings);

// Load modules (Set up dependencies)
foreach ($modules as $module => $params)
{
    if ( file_exists(__DIR__.'/Modules/'.$module.'/module.php') ) {
        require __DIR__.'/Modules/'.$module.'/module.php';
    }
}

// Register middleware
require __DIR__.'/middleware.php';

$app->run();