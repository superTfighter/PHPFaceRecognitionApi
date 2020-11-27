<?php

$container['globals'] = function ($container) {
    return new App\Modules\System\Globals\Globals($container);
};
