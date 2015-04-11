<?php

namespace Psecio\Invoke;

class RouteContainer
{
    private $route;
    private $config;

    public function __construct($route, $config)
    {
        $this->route = \Psecio\Invoke\Match::create('route.regex',
            array('route' => $route));
        $this->config = $config;
    }
    public function getPath()
    {
        return $this->reoute->getRoute();
    }
    public function getConfig()
    {
        return $this->config;
    }
}