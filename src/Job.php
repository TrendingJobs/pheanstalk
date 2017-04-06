<?php

namespace Pheanstalk;

/**
 * A job in a beanstalkd server.
 *
 * @author  Paul Annesley
 * @package Pheanstalk
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class Job
{
    const STATUS_READY = 'ready';
    const STATUS_RESERVED = 'reserved';
    const STATUS_DELAYED = 'delayed';
    const STATUS_BURIED = 'buried';

    private $_id;
    private $_data;
    static private $_dataHandler = [];

    /**
     * @param int    $id   The job ID
     * @param string $data The job data
     */
    public function __construct($id, $data)
    {
        $this->_id = (int) $id;

        foreach (self::$_dataHandler as $handler) {
            if ($handler->isAvailable($this)) {
                $data = $handler->encode($data);
            }
        }

        $this->_data = $data;
    }

    /**
     * The job ID, unique on the beanstalkd server.
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * The job data.
     *
     * @return string
     */
    public function getData()
    {
        $data = $this->_data;

        foreach (array_reverse(self::$_dataHandler) as $handler) {
            if ($handler->isAvailable($this)) {
                $data = $handler->decode($data);
            }
        }
        
        return $data;
    }

    /**
     * Sets a custom class that handles data encoding/decoding
     *
     * This can be useful, to compress or seperate data storage
     * from the queue, in order to reduce memory usage
     * 
     * @param \Pheanstalk\DataHandlerInterface $dataHandler
     */
    static public function setDataHander(DataHandlerInterface $dataHandler)
    {
        if (!self::$_dataHandler) {
            self::$_dataHandler = new \SplObjectStorage;
        }
        
        self::$_dataHandler->attach($dataHandler);
    }
}
