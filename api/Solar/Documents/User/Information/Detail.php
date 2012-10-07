<?php

namespace Documents;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="users")
 */
class User {

    /** @ODM\Id */
    private $id;
    
    /** @ODM\Field(type="bigint") */
    private $user_id;
    
    /** @ODM\Field(type="string") */
    private $hash;
    
    /** @ODM\Field(type="string") */
    private $username;
    
    /** @ODM\Field(type="string") */
    private $password;
    
    /** @ODM\Field(type="string") */
    private $status_code;
    
    /** @ODM\Field(type="string") */
    private $created;
    
    /** @ODM\Field(type="string") */
    private $activation_key;
    
    /** @ODM\Field(type="string") */
    private $activation_time;
    
    /** @ODM\Field(type="string") */
    private $last_login;
    
    private $information = array();
    
    private $counter = array();

}