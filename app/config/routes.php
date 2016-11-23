<?php
/*
 * Define custom routes. File gets included in the router service definition.
 */
$router = new Phalcon\Mvc\Router();


$router->add('/auth/facebook', array(
    'controller' => 'auth',
    'action' => 'facebook'
));
$router->add('/auth/twitter', array(
    'controller' => 'auth',
    'action' => 'twitter'
));
$router->add('/auth/gplus', array(
    'controller' => 'auth',
    'action' => 'gplus'
));


return $router;
