<?php

namespace Just\Amp\Laravel;

use Illuminate\Config\Repository;
use Illuminate\Routing\Router;
use Just\Amp\Exceptions\AmpRouteActionMustBeArray;
use Just\Amp\Exceptions\AmpRouteNameMustBeDefined;

class AmpRouter
{
    /**
     * @var \Illuminate\Routing\Router
     */
    private $router;

    /**
     * @var array
     */
    private $config;

    /**
     * @param \Illuminate\Routing\Router    $router
     * @param \Illuminate\Config\Repository $config
     */
    public function __construct(Router $router, Repository $config)
    {
        $this->router = $router;
        $this->config = $config;
    }

    /**
     */
    public function registerMacros()
    {
        $router = $this->router;
        $config = $this->config;

        $router->macro('amp', function ($url, $action) use ($router, $config)
        {
            $amp_url = $url;

            if ($config->get('amp.prefix')) {
                $prefixed = trim($config->get('amp.prefix', 'gm'), '/');
                $url = trim($url, '/');

                $amp_url = sprintf('%s/%s', $prefixed, $amp_url);
            }

            if ($config->get('amp.suffix')) {
                $suffixed = trim($config->get('amp.suffix'), '/');
                $url = trim($url, '/');

                $amp_url = sprintf('%s/%s', $amp_url, $suffixed);
            }

            if (! is_array($action)) {
                throw new AmpRouteActionMustBeArray(sprintf('Action for route [%s] must be an array', $url));
            }

            if (! isset($action['as'])) {
                throw new AmpRouteNameMustBeDefined(sprintf('There\'s no route name defined for [%s]', $url));
            }

            $ampRouteName = $action['as'] . '.amp';

            $router->get($url, array_merge($action, ['amp' => $ampRouteName]));
            $router->get($amp_url, array_merge($action, ['as' => $ampRouteName]));
        });
    }
}
