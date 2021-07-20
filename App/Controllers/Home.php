<?php

namespace App\Controllers;

use Core\DB;
use Core\Session;
use Core\User;
use Core\View;

/**
 * Home controller
 *
 * PHP version 5.4
 */
class Home extends \Core\Controller
{

    protected function before()
    {

    }


    protected function after()
    {
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $contacts=[];
        $contact = new \App\Model\Contact();
        $user = new User();

        if(!$user->isLoggedIn()){
            $isLoggedIn= 0;
            $user_name = '';
            View::renderTemplate('Home/index.html.twig');
        }else {
            $isLoggedIn = 1;
            $user_name = $user->data()->username;
            $user_id = $user->data()->id;
            $contacts=$contact->getContacts($user_id);

            View::renderTemplate('Home/index.html.twig',[
                'isLoggedIn'=>$isLoggedIn,
                'username'=> $user_name,
                'user_id'=>$user->data()->id,
                'contactList'=>$contacts
            ]);
        }
    }



}
