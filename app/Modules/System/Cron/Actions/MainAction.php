<?php

namespace App\Modules\System\Cron\Actions;

use App\Traits\CoreTrait;

use Slim\Http\Request;
use Slim\Http\Response;

class MainAction
{
    use CoreTrait;

    public function index(Request $request, Response $response, $args) {

        $queryParams = $request->getQueryParams();

        if (isset($queryParams['key']) && $queryParams['key'] == $this->settings['settings']['System']['Cron']['key']) {
            
            // Cron methods: https://github.com/peppeocchi/php-cron-scheduler
            $this->cron->call(function () {
                // Amit meghív
            })->at('* * * * *');

            $this->cron->run();
        }

    }

}