<?php

namespace App\Modules\Api\Actions;

use App\Traits\CoreTrait;

use Slim\Http\Request;
use Slim\Http\Response;

class ApiAction
{
    use CoreTrait;
    
    public function test($request,$response,$args)
    {
        echo "ASD";

    }
}
