<?php

// simpleSAMLphp
$container['saml'] = function ($container) {
    return new \SimpleSAML_Auth_Simple('default-sp');
};
