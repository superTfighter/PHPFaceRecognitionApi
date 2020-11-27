<?php

// slim-session
$container['session'] = function ($container) {
    return new \SlimSession\Helper;
};
