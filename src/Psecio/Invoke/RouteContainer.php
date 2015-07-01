<?php

namespace Psecio\Invoke;

class RouteContainer
{
    /**
     * Current route (match) instance
     * @var \Psecio\Invoke\MatchInstance
     */
    private $route;

    /**
     * Current configuration
     * @var array
     */
    private $config;

    /**
     * Parameters found in route processing
     * @var array
     */
    private $params = [];

    /**
     * Initialize the container with the provided route and
     *     configuration information
     *
     * @param string $route Route to provide to the match
     * @param array $config Route configuration
     */
    public function __construct($route, array $config)
    {
        $this->route = \Psecio\Invoke\Match::create('route.regex',
            array('route' => $route));
        $this->config = $config;
    }

    /**
     * Get the current configuration
     *
     * @return array Configuration set
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get the current route instance
     *
     * @return \Psecio\Invoke\MatchInstance
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set the parameters for the current route match
     *
     * @param array $params Parameter set
     */
    public function setParams(array $params = array())
    {
        $this->params = $params;
    }

    /**
     * Get the current parameter set
     *
     * @return array Parameter set
     */
    public function getParams()
    {
        return $this->params;
    }
}