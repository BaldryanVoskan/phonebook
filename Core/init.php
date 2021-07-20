<?php
if(!isset($_SESSION))
{
    session_start();
}

use Core\Config;
use Core\DB;
use Core\Cookie;
use Core\Session;
use Core\User;


$GLOBALS['config'] = array(
    'mysql'=> array(
        'host'=>'localhost',
        'username'=>'root',
        'password'=>'',
    ),
    'remember'=>array(
        'cookie_name'=>'hash',
        'cookie_expiry'=>'604800'
    ),
    'session'=>array(
        'session_name'=>'user',
        'token_name'=>'token'
    ),
);

require '../Core/createDb.php';

spl_autoload_register(function ($class) {
    require_once  $class. '.' .'php';
    require_once '../App/Views/sanitize.php';
});

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('users_session',array('hash','=',$hash));
    if($hashCheck->count()) {
        $user = new User($hashCheck->first()->user_id);
        $user->login();
    }
}



