<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

$app->get('/', 'GrEduLabs\\Action\\Index')->setName('index');

// authentication
$app->group('/user', function () {

    $this->map(['GET', 'POST'], '/login', 'GrEduLabs\\Action\\User\\Login')
        ->setName('user.login')
        ->add('csrf');

    $this->get('/login-sso', 'GrEduLabs\\Action\\User\\LoginSso')
        ->setName('user.loginSso');

    $this->get('/logout', 'GrEduLabs\\Action\\User\\Logout')
        ->setName('user.logout')
        ->add('authentication_cas_logout_middleware');

    $this->get('/profile', 'GrEduLabs\\Action\\User\\Profile')
        ->setName('user.profile');
});
