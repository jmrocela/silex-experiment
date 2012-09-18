<?php

namespace Documents;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="sessions")
 */
class Session {

    /** @ODM\Id */
    private $id;

    public function getId()
    {
        return $this->id;
    }

	/** @ODM\Field(type="string") */
    private $sess_id;

    public function setSessId($val = null)
    {
    	$this->sess_id = $val;
    }

    public function getSessId()
    {
    	return $this->sess_id;
    }

    /** @ODM\Field(type="string") */
    private $data;

    public function setData($val = null)
    {
    	$this->data = $val;
    }

    public function getData()
    {
    	return $this->data;
    }

    /** @ODM\Field(type="string") */
    private $time;

    public function setTime($val = null)
    {
    	$this->time = ($val == null) ? time(): $val;
    }

    public function getTime()
    {
    	return $this->time;
    }

    /** @ODM\Field(type="string") */
    private $IP;

    public function setIP($val = null)
    {
        $this->IP = ($val == null) ? time(): $val;
    }

    public function getIP()
    {
        return $this->IP;
    }

    /** @ODM\Field(type="string") */
    private $useragent;

    public function setUseragent($val = null)
    {
        $this->useragent = ($val == null) ? time(): $val;
    }

    public function getUseragent()
    {
        return $this->useragent;
    }

}