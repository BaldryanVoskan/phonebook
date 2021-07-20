<?php


use Core\Router;
use Core\User;


require '../vendor/autoload.php';
require '../Core/init.php';

$router = new Router();
$user = new User();

$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('Signup',['controller'=>'Signup','action'=>'index']);
$router->add('Login',['controller'=>'Login','action'=>'index']);
$router->add('Logout',['controller'=>'Logout','action'=>'index']);




$router->dispatch($_SERVER['QUERY_STRING']);
