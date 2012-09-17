<?php

namespace Solar;

use Documents\Session;

class SessionHandler implements \SessionHandlerInterface {
    /**
     * @var Doctrine\MongoDB\Connection instance.
     */
    private $db;

    private $session;

    public function __construct(\Doctrine\ODM\MongoDB\DocumentManager $db, $options = array())
    {
    	if (!array_key_exists('db_table', $options)) {
            throw new \InvalidArgumentException('You must provide the "db_table" option for SessionStorage.');
        }

        $this->db = $db;
        $this->options = array_merge(array(
            'id_col'   => 'sess_id',
            'data_col' => 'sess_data',
            'time_col' => 'sess_time',
        ), $options);

        // we create an instance of the Session Document
        $this->session = new Session();
    }

    /**
     * {@inheritDoc}
     */
    public function open($path, $name)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function read($key) {}

    /**
     * {@inheritDoc}
     */
    public function write($key, $val) {}

    /**
     * {@inheritDoc}
     */
    public function destroy($key) {
        try {
            $this->db->getRepository('Documents\Session')->findBy(array('sess_id' => $key));
        } catch (\PDOException $e) {
            throw new \RuntimeException(sprintf('PDOException was thrown when trying to manipulate session data: %s', $e->getMessage()), 0, $e);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function gc($maxlifetime) {}

}