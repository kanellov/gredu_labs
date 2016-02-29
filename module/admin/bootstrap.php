<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

return function (Slim\App $app) {
    $container = $app->getContainer();
    $events    = $container['events'];

    $events('on', 'app.autoload', function ($stop, $autoloader) {
        $autoloader->addPsr4('GrEduLabs\\Admin\\', __DIR__ . '/src/');
    });

    $events('on', 'app.services', function ($stop, $container) {

        $container[GrEduLabs\Admin\Action\Index::class] = function ($c) {
            return new GrEduLabs\Admin\Action\Index(
                $c->get('view')
            );
        };

        $container[GrEduLabs\Admin\Action\Users\ListUsers::class] = function ($c) {
            return new GrEduLabs\Admin\Action\Users\ListUsers(
                $c->get('view')
            );
        };

        $container[GrEduLabs\Admin\Action\Schools\ListSchools::class] = function ($c) {
            return new GrEduLabs\Admin\Action\Schools\ListSchools(
                $c->get('view'),
                $c->get(GrEduLabs\Schools\Service\SchoolServiceInterface::class)
            );
        };

    });

    $events('on', 'app.bootstrap', function ($stop, $app, $container) {

        $container['view']->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');

        $app->group('/admin', function () {
            $this->get('', GrEduLabs\Admin\Action\Index::class)->setName('admin.index');
            $this->get('/users', GrEduLabs\Admin\Action\Users\ListUsers::class)->setName('admin.users');
            $this->get('/schools', GrEduLabs\Admin\Action\Schools\ListSchools::class)->setName('admin.schools');
            $this->get('/application-forms', function () {})->setName('admin.application_forms');
        });
    });
};
