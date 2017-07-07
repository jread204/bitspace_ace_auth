<?php
/**
 * Created by PhpStorm.
 * User: James
 * Date: 2017-06-30
 * Time: 12:11 PM
 */

namespace Ace_auth;


interface SessionDelegate
{
    
    public function authDeleteSession(string $session_id);

    public function authReadSession(string $session_id );

    public function authWriteSession(string $session_id , string $session_data );

}