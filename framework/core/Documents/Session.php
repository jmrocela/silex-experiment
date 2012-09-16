<?php

namespace Documents;

class Session {

	/** @Id */
    private $sess_id;

    public function setSessId($val = null)
    {
    	$this->sess_id = $val;
    }

    public function getSessId()
    {
    	return $this->sess_id;
    }

    /** @String */
    private $sess_data;

    public function setSessData($val = null)
    {
    	$this->sess_data = $val;
    }

    public function getSessData()
    {
    	return $this->sess_data;
    }

    /** @String */
    private $sess_time;

    public function setSessTime($val = time())
    {
    	$this->sess_time = $val;
    }

    public function getSessTime()
    {
    	return $this->sess_time;
    }

}