<?php
/**
 * Created by PhpStorm.
 * User: James
 * Date: 2017-06-30
 * Time: 12:17 PM
 */

namespace Ace_auth;


interface StorageDelegate
{
    public function fetchUser($search_by,$value);

    public function fetchUserById($id);

    public function WriteNewUser($username,$password,$email,$name,$role,$permissionLevel);

    public function fetchUserByToken($id);

    public function remember_me($token);

}