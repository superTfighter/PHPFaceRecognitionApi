<?php

// Twig
$container['view'] = function($container) {
    $settings = $container->get('settings')['settings']['System']['View'];

    $twigPath = array();
    $twigPath['__main__'] = $settings['template_path'];

    // Load module templates
    $modules = $container->get('settings')['modules'];
    
    foreach ($modules as $module => $params)
    {
        $templateDir = __DIR__ . '/../../'.$module.'/Resources/templates';
        if ( file_exists($templateDir) ) {
            $twigPath[$module] = $templateDir;
        }
    }

    // Twig Extension   
    $view = new \Slim\Views\Twig($twigPath, $settings['twig']);

    $view->addExtension( new Slim\Views\TwigExtension($container['router'], $container['request']->getUri() ));
    $view->addExtension( new Twig_Extension_Debug() );

    $view->addExtension( new App\Modules\System\View\TwigExtensions($container) );

    // Twig Specials Variables
    $view->getEnvironment()->addGlobal("cookie", @$_COOKIE);
    $view->getEnvironment()->addGlobal("dev_mode", $container->get('settings')['dev_mode']);

    return $view;
};
