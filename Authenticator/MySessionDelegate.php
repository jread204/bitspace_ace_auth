<?php
/**
 * Created by PhpStorm.
 * User: James
 * Date: 2017-07-04
 * Time: 9:50 AM
 */

namespace Ace_auth;


class MySessionDelegate implements SessionDelegate
{
    function __construct()
    {
       session_start();
    }

    public function authDeleteSession(string $session_id)
    {
        $file = "Session/sess_$session_id";
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }

    public function authReadSession(string $session_id)
    {
       return (string)@file_get_contents("Session/sess_$session_id");
    }

    public function authWriteSession(string $session_id, string $session_data)
    {
       return file_put_contents("Session/sess_$session_id", $session_data) === false?false:true;
    }

    public function authWriteData($instance_name,$auth_data)
    {
        $_SESSION['Ace_Auth'][$instance_name] = $auth_data;
        return true;
    }
    public function authReadData($instance_name)
    {
        return (isset($_SESSION['Ace_Auth'][$instance_name])) ?
            $_SESSION['Ace_Auth'][$instance_name] :
            false;
    }
}