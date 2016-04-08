<?php

use GrEduLabs\Authorization\Acl;
use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

return function (App $app) {
    $container = $app->getContainer();
    $events    = $container['events'];

    $events('on', 'app.services', function (Container $c) {
        $c[Acl::class] = $c->extend(Acl::class, function (Acl $acl, Container $c) {
            $acl->deny('guest', ['route/user/login', 'route/user/login/sso'], ['get', 'post']);
            $router = $c['router'];
            $denyRoutes = array_reduce($router->getRoutes(), function ($denyRoutes, $route) {
                $denyRoutes[] = 'route' . $route->getPattern();

                return $denyRoutes;
            }, []);
            $acl->deny('user', $denyRoutes, ['get', 'post']);
            $acl->deny('school', $denyRoutes, ['get', 'post']);

            return $acl;
        });
    }, -1000);

    $events('on', 'app.bootstrap', function (App $app, Container $c) {
        // clear session
        
        $c['authentication_service']->clearIdentity();
        
        $router = $c['router'];
        $router->getNamedRoute('index')->add(function (Request $req, Response $res, $next) use ($c) {
            $view = $c->get('view');
            /* @var $view Twig */

            $namespaces = $view->getEnvironment()->getLoader()->getNamespaces();
            $namespace = in_array('application', $namespaces) ? 'application' : Twig_Loader_Filesystem::MAIN_NAMESPACE;
            $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates', $namespace);

            return $next($req, $res);
        });

        $router->getNamedRoute('user.login')->add(function (Request $req, Response $res, $next) use ($router) {

            return $res->withRedirect($router->pathFor('index'));
        });
    });
};