<?php
namespace public;

class Globals
{



    function body()
    {
        $GLOBALS['host'] = 'localhost';
        $GLOBALS['username'] = 'root';
        $GLOBALS['password'] = '';
        $GLOBALS['db_name'] = 'myphonebook';
        $GLOBALS['users'] = 'users';



        echo $GLOBALS['host'];
        echo $GLOBALS['username'];
        echo $GLOBALS['password'];
        echo $GLOBALS['db_name'];
        echo $GLOBALS['users'];
    }

}