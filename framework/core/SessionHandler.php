<?php

namespace Solar;

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
    public function read($key) 
    {
        try {
            $session = $this->db->createQueryBuilder('\Documents\Session')->field('sess_id')->equals($key)->getQuery()->getSingleResult();
            if ($session = base64_decode($session)) {
                return $session;
            }

            $this->create($key);
            return '';
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf('Exception was thrown when trying to read the session data: %s', $e->getMessage()), 0, $e);
        }

        return true;
    }

    public function create($key, $data = '')
    {
        $session = new \Documents\Session();
        $session->setSessId($key);
        $session->setSessData(base64_encode($data));
        $session->setSessTime(time());

        $this->db->persist($session);
        $this->db->flush();

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function write($key, $data) 
    {
        //session data can contain non binary safe characters so we need to encode it
        $encoded = base64_encode($data);

        try {
            $session = $this->db->createQueryBuilder('\Documents\Session')->field('sess_id')->equals($key)->getQuery()->getSingleResult();

            if (!$session) {
                $this->create($key, $data);
            }
        } catch (\Exception $e) {
                throw new \RuntimeException(sprintf('Exception was thrown when trying to write the session data: %s', $e->getMessage()), 0, $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function destroy($key) 
    {
        try {
            $this->db->createQueryBuilder('\Documents\Session')->remove()->field('sess_id')->equals($key)->getQuery()->execute();
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf('Exception was thrown when trying to manipulate session data: %s', $e->getMessage()), 0, $e);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function gc($maxlifetime) 
    {
        try {
            $this->db->createQueryBuilder('\Documents\Session')->remove()->field('sess_time')->lt($maxlifetime)->getQuery()->execute();
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf('Exception was thrown when trying to manipulate session data: %s', $e->getMessage()), 0, $e);
        }

        return true;
    }

}