<?php


namespace App\Controllers;


use Core\Controller;
use Core\DB;
use Core\Hash;
use Core\Input;
use Core\Redirect;
use Core\Token;
use Core\User;
use Core\Validate;
use Core\View;
use Exception;

class Contact extends \Core\Controller
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

        $user = new User();
        $user_id = $user->data()->id;
        $username = $user->data()->username;
        if (!$user->isLoggedIn()) {
            Redirect::to('/');
        }
        if (Input::exists()) {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'contactname' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 20
                ),
                'contactaddress' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 50,
                ),
                'number1' => array(
                    'required' => true,
                    'min' => 4,
                    'max' => 20
                )
            ));
            if ($validation->passed()) {
                try {

                    DB::getInstance()->insert('contacts', array(
                        'user_id' => $user_id,
                        'ContactName' => Input::get('contactname'),
                        'ContactAddress' => Input::get('contactaddress'),
                        'Number_1' => Input::get('number1'),
                        'Number_2' => Input::get('number2'),
                        'Number_3' => Input::get('number3')
                    ));
                    Redirect::to('/Home/' . $user->data()->id . '/' . 'index');
                } catch (Exception $e) {

                    die(($e->getMessage()));
                }
            }
        }

        View::renderTemplate('Contact/createcontact.html.twig', [
            'username'=>$username,
            'user_id' => $user->data()->id
        ]);
    }


    public function editAction()
    {

        $contact = new \App\Model\Contact();
        $user = new User();
        $user_id = $user->data()->id;
        $username = $user->data()->username;
       $contact_item = '';
        if (!$user->isLoggedIn()) {
            Redirect::to('/');
        }

        if (Input::exists()) {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'newcontactname' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 20
                ),
                'newcontactaddress' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 70,
                ),
                'newnumber1' => array(
                    'required' => true,
                    'min' => 4,
                    'max' => 20
                )
            ));
            if ($validation->passed()) {
                try {
                    $contact->update('contacts', Input::get('contact_id'), array(
                        'user_id' => $user_id,
                        'ContactName' => Input::get('newcontactname'),
                        'ContactAddress' => Input::get('newcontactaddress'),
                        'Number_1' => Input::get('newnumber1'),
                        'Number_2' => Input::get('newnumber2'),
                        'Number_3' => Input::get('newnumber3')
                    ));

                    Redirect::to('/Home/' . $user->data()->id . '/' . 'index');
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            }
        }

            if($contact_item == ''){
                $contact_item=$contact->getContact($this->route_params['id']);
                View::renderTemplate('Contact/edit.html.twig', [
                    'username'=>$username,
                    'contact' => $contact_item->user_id == $user_id ? $contact_item : null
                ]);
            }else {
                View::renderTemplate('Contact/edit.html.twig', [
                    'username'=>$username
                ]);
            }
    }

    public function deleteAction()
    {
        $contact = new \App\Model\Contact();
        $user = new User();

        $contacts = $contact->getContact($this->route_params['id']);
        $contact_id = $contacts->id;

        $contact->delete('contacts', $contact_id);
        Redirect::to('/Home/'.$user->data()->id.'/index');
    }

    public function changepasswordAction() {
        $errors = [];
        $user = new User();
        $username = $user->data()->username;
        if(!$user->isLoggedIn()){
            Redirect::to('index.php');
        }
        if(Input::exists()) {
            if(Token::check(Input::get('token'))) {
                $validate = new Validate();
                $validation = $validate->check($_POST,array(
                    'password_current' => array(
                        'required'=>true,
                        'min'=>6
                    ),
                    'password_new' => array(
                        'required'=>true,
                        'min'=>6
                    ),
                    'password_new_again' => array(
                        'required'=>true,
                        'min'=>6,
                        'matches'=>'password_new'
                    )
                ));

                if($validation->passed()){
                    if(Hash::make(Input::get('password_current'),$user->data()->salt) !== $user->data()->password){
                        echo'Your current password is wrong';
                    }else{
                        $salt = Hash::salt(16);
                        $id = $user->data()->id;
                        $user->update('users', $id,array(
                            'password'=>Hash::make(Input::get('password_new'), $salt),
                            'salt'=>$salt
                        ));
                        Redirect::to('/Home/4/index');
                    }

                }else {
                    $errors = $validation->errors();
                }
            }
        }
        View::renderTemplate('Changepassword/changepassword.html.twig',[
            'errors' => $errors,
            'username'=>$username,
            'token'=>Token::generate()
        ]);
    }
}