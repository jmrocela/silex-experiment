<?php

namespace Documents;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="users")
 */
class User {

    /** @ODM\Id */
    private $id;

    public function getId()
    {
        return $this->id;
    }

	/** @ODM\Field(type="string") */
    private $user_id;

    public function setUserId($val = null)
    {
    	$this->user_id = $val;
    }

    public function getUserId()
    {
    	return $this->user_id;
    }

	/** @ODM\Field(type="string") */
    private $username;

    public function setUsername($val = null)
    {
    	$this->username = $val;
    }

    public function getUsername()
    {
    	return $this->username;
    }

	/** @ODM\Field(type="string") */
    private $password;

    public function setPassword($val = null)
    {
    	$this->password = $val;
    }

    public function getPassword()
    {
    	return $this->password;
    }

	/** @ODM\Field(type="string") */
    private $email;

    public function setEmail($val = null)
    {
    	$this->email = $val;
    }

    public function getEmail()
    {
    	return $this->email;
    }

	/** @ODM\Field(type="string") */
    private $first_name;

    public function setFirstName($val = null)
    {
    	$this->first_name = $val;
    }

    public function getFirstName()
    {
    	return $this->first_name;
    }

	/** @ODM\Field(type="string") */
    private $last_name;

    public function setLastName($val = null)
    {
    	$this->last_name = $val;
    }

    public function getLastName()
    {
    	return $this->last_name;
    }

}