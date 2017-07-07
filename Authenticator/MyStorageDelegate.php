<?php
/**
 * Created by PhpStorm.
 * User: James
 * Date: 2017-07-04
 * Time: 10:13 AM
 */

namespace Ace_auth;


class MyStorageDelegate implements StorageDelegate
{
    private $dsn;

    private $options;

    private $host;

    private $dbname;

    private $user = 'root';

    private $pw = null;
    /**
 * MyStorageDelegate constructor.
 * @param $host
 * @param $dbname
 * @param $user
 * @param $pw
 */public function __construct($host , $dbname, $user , $pw )
{
    $this->host = $host;
    $this->dbname = $dbname;
    $this->user = $user;
    $this->pw = $pw;

    $dsn = "mysql:dbname=$this->dbname;host=$this->host";
    $options = array(
        PDO::ATTR_PERSISTENT    => true,
        PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
    );


}

    private function connect()
    {
        return $db = new \PDO($this->dsn,$this->user,$this->pw,$this->options);
    }
    public function fetchUser($search_by, $value)
    {
        $db = $this->connect();

        $query = $db->prepare('select * from users where :search_by = :value');
        $query->bindParam(':search_by', $search_by);
        $query->bindParam(':value', $value);

        return $query->fetch();

    }

    public function fetchUserById($id)
    {
        $db = $this->connect();

        $query = $db->prepare('select * from users where id = :id');
        $query->bindParam(':id', $id);
        return $query->fetch();
    }

    public function fetchUserByToken($id)
    {
        $db = $this->connect();

        $query = $db->prepare('select * from users where remembertoken = :id');
        $query->bindParam(':id', $id);
        return $query->fetch();
    }
    public function WriteNewUser($username, $password, $email, $name, $role, $permissionLevel)
    {
        if ($username || $email) {
            $db = $this->connect();
            $query = $db->prepare('INSERT INTO users (username,password, email, name, role, permissionLevel) VALUES (:username,:password,:email,:name,:role,:permissionLevel)');
            $query->bindParam(':username', $username);
            $query->bindParam(':email', $email);
            $query->bindParam(':name', $name);
            $query->bindParam(':role', $role);
            $query->bindParam(':permissionLevel', $permissionLevel);
            $query->execute();

            return $db->lastInsertId();
        }
    }

    public function remember_me($token)
    {
        $db = $this->connect();
        $query = $db->prepare('INSERT INTO users (remember_me) VALUES (:toked)');
        $query->bindParam(':token', $token);
    }
}