<?php

namespace App\Traits;

trait CoreTrait {

    protected $container;
    protected $options;

    public function __construct($container, $options = array())
    {
        $this->container  = $container;

        // Options
        foreach ($options as $key => $value) {
            $this->{$key}  = $value;
        }

    }

    public function __get($property)
    {
        if (isset($this->container->{$property})) {
            return $this->container->{$property};
        }
    }

}
