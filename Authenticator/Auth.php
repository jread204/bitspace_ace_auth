<?php
/**
 * Created by PhpStorm.
 * User: James
 * Date: 2017-06-30
 * Time: 11:33 AM
 */

namespace Ace_auth;
use Ace_auth\SessionDelegate;
use Ace_auth\StorageDelegate;
use Ace_auth\User;
use Doctrine\Common\Collections\ArrayCollection;
class Auth
{

    protected $name;
    protected $session;
    protected $storage;
    protected $options;
    protected $user;
    protected $groups = array();

    public function __construct($name, SessionDelegate $session, StorageDelegate $storage,array $options = array())
    {
        $this->name = $name;
        $this->session = $session;
        $this->storage = $storage;
        
        $defaultOptions = array(
            'cost' => 8,
            'expire_in'=>7200
        );

        $this->options = array_merge($defaultOptions, $options);

    }

    function name()
    {
        return $this->name;
    }

    function hash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, array('cost' => $this->options['cost']));
    }

    function verify($password,$hash)
    {
        return password_verify($password, $hash);
    }

    function login(string $search_by, string $value,string $password,bool $remember_me)
    {
        if (!isset($_COOKIE['ace_auth'])) {
            $user = $this->storage->fetchUser($search_by, $value);
            if (!$user) {
                return false;
            }
            if (!$this->verify($password, $user['password'])) {
                return false;
            }

            $this->user = new User($user);
            $this->session->authWriteSession($this->name(), $this->user->getId());
            if ($remember_me == true)
            {
                $expire_in = $this->options['expire_in'];
                $this->remember_login($expire_in);

            }
            return true;
        }

        $user = $this->storage->fetchUserByToken($_COOKIE['remember_me']);
        if (!$user)
        {
            return false;
        }
        if (!$this->verify($password, $user['password'])) {
            return false;
        }
        $this->user = new User($user);
        $this->session->authWriteSession($this->name(), $this->user->getId());
        return true;

    }

    function isLoggedIn()
    {
        if(isset($var))
        return (bool)$this->session->authReadSession($this->name());
    }

    function logout()
    {
        $this->session->authDeleteSession($this->name());
            $this->user=false;
    }

    function CreateNewUser($username,$password,$email,$name,$role,$permissionLevel)
    {
        $clean_username = filter_var($username,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $clean_password = filter_var($password,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $clean_password = $this->hash($clean_password);
        $clean_email = filter_var($email,FILTER_SANITIZE_EMAIL);
        $clean_name = filter_var($name,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $clean_role = filter_var($role,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $clean_permissionLevel = filter_var($permissionLevel,FILTER_SANITIZE_NUMBER_INT);
        $clean_permissionLevel  = filter_var($clean_permissionLevel,FILTER_VALIDATE_INT,["options" =>["min_range"=>0,"max_range"=>10]]);

        $id = $this->storage->WriteNewUser($clean_username,$clean_password,$clean_email,$clean_name,$clean_role,$clean_permissionLevel);

        if(isset($id))
        {
            $this->session->authWriteSession($this->name(),$id);
            return true;
        }

        return false;
    }

    function Role($search_by,$value)
    {
        $this->user =  $this->loadUserRepresentation();
       $user = $this->storage->fetchUserById($this->user->getId());
        if (!$user)
        {
            return false;
        }

        return $this->user->getRole();
    }

    function checkRole($checkValue)
    {
        $this->user =  $this->loadUserRepresentation();
        $user = $this->storage->fetchUserById($this->user->getId());
        if (!$user)
        {
            return false;
        }
            if ($this->user->getRole() === $checkValue)
            {
                return true;
            }
       return false;
    }

    function Premission()
    {
        $this->user =  $this->loadUserRepresentation();
        $user = $this->storage->fetchUserById($this->user->getId());
        if (!$user)
        {
            return false;
        }

        return $this->user->getRole();
    }

    function checkPremission($checkValue)
    {
       $this->user =  $this->loadUserRepresentation();
        $user = $this->storage->fetchUserById($this->user->getId());
        if (!$user)
        {
            return false;
        }
            if ($this->user->getPermissionLevel() === $checkValue)
            {
                return true;
            }
        return false;
    }

    protected function loadUserRepresentation()
    {
        if (!isset($this->user))
        {
            if (!$this->isLoggedIn()) {
                return false;
            }

            return new User($this->session->authReadSession($this->name()));
        }
    }

    /**
     * @param $expire_in
     */
    public function remember_login($expire_in): void
    {

        $token = \Sodium\bin2hex(random_bytes(256));

        $this->storage->remember_me($token);

        setcookie('remember_me', $token, time() + $expire_in);

    }

    protected function JSONloadUserRepresentation()
    {
        if (!isset($this->user))
        {
            if (!$this->isLoggedIn())
            {
                return false;
            }
           $this->user = new User($this->session->authReadSession($this->name()));

            return json_encode($this->user->user_array());

        }
    }
}