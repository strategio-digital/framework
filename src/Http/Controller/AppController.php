<?php
/**
 * Copyright (c) 2022 Strategio Digital s.r.o.
 * @author Jiří Zapletal (https://strategio.dev, jz@strategio.dev)
 */
declare(strict_types=1);

namespace Saas\Http\Controller;

use Nette\DI\Container;
use Saas\Helper\Path;
use Saas\Helper\Router;
use Saas\Http\Controller\Base\Controller;
use Saas\Storage\Storage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;

class AppController extends Controller
{
    public function app(Container $container, string|int|float $uri = null): Response
    {
        /** @var \Symfony\Component\Routing\RouteCollection $routes */
        $routes = $container->getByName('routes');
        
        /** @var \Symfony\Component\Routing\Route $route */
        $route = $routes->get(Router::ROUTE_APP);
        $appPath = $route->compile()->getStaticPrefix();
        
        return $this->render(Path::saasVendorDir() . '/view/controller/admin.latte', [
            'appPath' => $appPath,
        ]);
    }
    
    public function api(Storage $storage, Container $container): Response
    {
        /** @var \Symfony\Component\Routing\RouteCollection $routes */
        $routes = $container->getByName('routes');
        
        $prettyRoutes = array_map(function (Route $route) {
            $options = array_filter($route->getOptions(), fn($key) => $key !== 'compiler_class', ARRAY_FILTER_USE_KEY);
            
            return [
                'path' => $route->getPath(),
                'methods' => count($route->getMethods()) ? $route->getMethods() : null,
                'options' => count($options) ? $options : null,
                'route_rules' => count($route->getRequirements()) ? $route->getRequirements() : null,
                'schema_rules' => '@not-implemented-yet'
            ];
            
        }, $routes->all());
        
        $dt = new \DateTime();
        
        return $this->json([
            'name' => $_ENV['APP_NAME'],
            'mode' => $_ENV['APP_ENV_MODE'],
            'log_adapter' => $_ENV['LOG_ADAPTER'],
            'storage_adapter' => $storage->getAdapterName(),
            'execution_time' => floor((microtime(true) - $container->parameters['startedAt']) * 1000) . 'ms',
            'current_dt' => [
                'date_time' => $dt->format('Y.m.d H:i:s:u'),
                'time_zone' => $dt->getTimezone()->getName()
            ],
            'endpoints' => [
                'count' => $routes->count(),
                'items' => $prettyRoutes
            ]
        ]);
    }
}