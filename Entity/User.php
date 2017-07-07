<?php
/**
 * Created by PhpStorm.
 * User: James
 * Date: 2017-06-30
 * Time: 12:26 PM
 */

namespace Ace_auth;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="users")
 */
class User
{
    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */protected $id;
    /**
     * @Column(type="string")
     */
    protected $username;
    /**
     * @Column(type="string")
     */
    protected $email;
    /**
     * @Column(type="string")
     */
    protected $name;
    /**
     * @Column(type="string")
     */
    protected $role;
    /**
     * @ORM\Column(type="string")
     */
    protected $permissionLevel = 0;
    /**
     * @Column(type="string")
     */
    protected $remember_me;

    /**
     * Many Users have Many Groups.
     * @ManyToMany(targetEntity="Group", inversedBy="users")
     * @JoinTable(name="users_groups")
     */
    private $groups;

    public function __construct(array $user)
    {
        $this->id = $user['id'];
        $this->username = $user['username'];
        $this->email = $user['email'];
        $this->name = $user['name'];
        $this->role = $user['role'];
        $this->permissionLevel = $user['permissionLevel'];
        $this->groups = new ArrayCollection();
    }

    public function assign_to_group(Group $group)
    {
        $this->groups[] = $group;

    }

    function get_groups()
    {
        return $this->groups;
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getPermissionLevel()
    {
        return $this->permissionLevel;
    }

    /**
     * @param mixed $permissionLevel
     */
    public function setPermissionLevel($permissionLevel)
    {
        $this->permissionLevel = $permissionLevel;
    }
    public function user_array()
    {
        return array(
            'id'=>$this->id,
            'username'=>$this->username,
            'email'=>$this->email,
            'name'=>$this->name,
            'role'=>$this->role,
            'permissionLevel'=>$this->permissionLevel
        );
    }

}
class UserRepository extends EntityRepository
{

}
