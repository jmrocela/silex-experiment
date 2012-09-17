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
    private $sess_data;

    public function setSessData($val = null)
    {
    	$this->sess_data = $val;
    }

    public function getSessData()
    {
    	return $this->sess_data;
    }

    /** @ODM\Field(type="string") */
    private $sess_time;

    public function setSessTime($val = null)
    {
    	$this->sess_time = ($val == null) ? time(): $val;
    }

    public function getSessTime()
    {
    	return $this->sess_time;
    }

}