<?php


namespace App\Controllers;
use Core\Controller;
use Core\Input;
use Core\Redirect;
use Core\Token;
use Core\User;
use Core\Validate;
use Core\View;

class Login extends Controller
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
    public function indexAction() {
        $errors=[];
        $login_fail = 0;
        if(Input::exists()) {
            if(Token::check(Input::get('token'))){
                $validate = new Validate();
                $validation = $validate->check($_POST,array(
                    'username'=>array('required'=>true),
                    'password'=>array('required'=>true)
                ));
                if($validation->passed()){
                    $user = new User();
                    $remember = (Input::get('remember') == '1') ? true: false;
                    $login = $user->login(Input::get('username'),Input::get('password'),$remember);
                    if($login){
                        Redirect::to('/Home/'.$user->data()->id.'/index');

                    }else {
                        $login_fail = 1;
                    }
                }else {
                    $errors = $validation->errors();
                }
            }
        }

        View::renderTemplate('Login/login.html.twig',[
            'login_fail'=>$login_fail,
            'username' => Input::get('username'),
            'token' => Token::generate(),'errors'=>$errors
        ]);
    }

}