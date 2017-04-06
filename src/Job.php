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
    private $_pheanstalk;

    /**
     * @param int    $id   The job ID
     * @param string $data The job data
     */
    public function __construct($id, $data, Pheanstalk $pheanstalk)
    {
        $this->_id = (int) $id;
        $this->_pheanstalk = $pheanstalk;

        foreach ($this->_pheanstalk->getDataHandler()->getList() as $handler) {
            $data = $handler->encode($data);
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
        $data = $this->getRawData();

        $handlers = [];
        foreach ($this->_pheanstalk->getDataHandler()->getList() as $handler) {
            array_unshift($handlers, $handler);
        }

        foreach ($handlers as $handler) {
            $data = $handler->decode($data);
        }

        return $data;
    }

    /**
     * Retrieves the raw job data
     *
     * @return string
     */
    public function getRawData()
    {
        return $this->_data;
    }

}
