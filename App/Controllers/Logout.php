<?php


namespace App\Controllers;


use Core\Controller;
use Core\Cookie;
use Core\DB;
use Core\Redirect;
use Core\Session;
use Core\User;

class Logout  extends Controller
{
    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction() {
        $user = new User();
        $user->logout();
        Redirect::to('/');
    }


}