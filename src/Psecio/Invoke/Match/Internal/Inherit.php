<?php

namespace Psecio\Invoke\Match\Internal;

class Inherit extends \Psecio\Invoke\MatchInstance
{
    /**
     * Execute the check
     *
     * @param \Psecio\Invoke\Data $data Data object instance
     * @return boolean Result of evaluation
     */
    public function evaluate($data)
    {
        $inherit = $data->route->getConfig('inherit');

        if ($inherit !== null) {
            $routes = $data->getEnforcer()->getConfig();

            // Find the one to inherit from
            foreach ($routes as $route) {
                if ($route->getConfig('name') === $inherit) {
                    $data->getEnforcer()->addMatch($route);
                    return true;
                }
            }
        }
        return false;
    }
}