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
        $contacts = [];
        $contact = new \App\Model\Contact();
        $user = new User();
        if (!$user->isLoggedIn()) {
            $isLoggedIn = 0;
            $user_name = '';
            View::renderTemplate('Home/index.html.twig');
        } else {
            $isLoggedIn = 1;
            $user_name = $user->data()->username;
            $user_id = $user->data()->id;
            $current_page = 1;
            $empty = '';
            $total_pages = '';
            if (isset($this->route_params['page'])) {
                $current_page = (int)htmlspecialchars($this->route_params['page']);
            }
            $items_per_page = 2;

            $offset = ($current_page - 1) * $items_per_page;
            $contacts = $contact->getContacts($user_id, $items_per_page, $offset);

            $allContacts = $contact->getAllContacts($user_id);

            if ($allContacts != FALSE) {
                $empty = TRUE;
                $allContacts = count($allContacts);
                $total_pages = round($allContacts / $items_per_page);
            }

            View::renderTemplate('Home/index.html.twig', [
                'isLoggedIn' => $isLoggedIn,
                'username' => $user_name,
                'user_id' => $user->data()->id,
                'page' => $current_page,
                'total_pages' => $total_pages,
                'allContacts' => $empty,
                'contactList' => $contacts
            ]);
        }
    }


}
