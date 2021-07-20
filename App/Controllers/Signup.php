<?php


namespace App\Controllers;

use Core\Controller;
use Core\DB;
use Core\Hash;
use Core\Validate;
use Core\View;
use Exception;
use Core\User;
use Core\Input;
use Core\Redirect;
use Core\Session;
use Core\Token;


class Signup extends Controller
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

$errors = [];
        if (Input::exists()) {
            if(Token::check(Input::get('token'))) {
                $validate = new Validate();
                $validation = $validate->check($_POST,array(
                    'username'=>array (
                        'required'=>true,
                        'min'=> 2,
                        'max'=>25,
                        'unique'=>'users'
                    ),
                    'fullname'=>array(
                        'required'=>true,
                        'min'=>2,
                        'max'=>50
                    ),
                    'password'=> array(
                        'required' => true,
                        'min'=>6
                    ),
                    'confirm'=>array(
                        'required'=>true,
                        'matches'=>'password'
                    )

                ));

                if($validation->passed()){
                    $user = new User();
                    $salt = Hash::salt(16);

                    try {
                        $user->create('users', array(
                            'username'=> Input::get('username'),
                            'fullname'=> Input::get('fullname'),
                            'email'=>Input::get('email'),
                            'password'=> Hash::make(Input::get('password'),$salt),
                            'salt'=> $salt,
                        ));
                        Redirect::to('/Login/index');
                    } catch (Exception $e){

                        die(($e->getMessage()));
                    }
                }else {
                    $errors = $validation->errors();
                }
            }
        }
        View::renderTemplate('Signup/signup.html.twig', ['token' => Token::generate(),'errors'=>$errors]);
    }
}