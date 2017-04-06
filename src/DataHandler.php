<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pheanstalk;

/**
 * Description of DataHandler
 *
 * @author stephan
 */
class DataHandler {

    /** @var SplSubject[] list of listeners */
    private $_handlers;
    
    /** @var DataHandler instance */
    static private $_instance;
    
    /** 
     * Retrieves an instance 
     */
    static public function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

     /**
     * Sets a custom class that handles data encoding/decoding
     *
     * This can be useful, to compress or seperate data storage
     * from the queue, in order to reduce memory usage
     *
     * @param \Pheanstalk\DataHandlerInterface $dataHandler
     */
    public function attach(DataHandlerInterface $dataHandler)
    {
        $this->getList()->attach($dataHandler);
        return $this;
    }

    /**
     * Removes a custom class that handles data encoding/decoding
     *
     * This can be useful, to compress or seperate data storage
     * from the queue, in order to reduce memory usage
     *
     * @param \Pheanstalk\DataHandlerInterface $dataHandler
     */
    public function detach(DataHandlerInterface $dataHandler)
    {
        $this->getList()->detach($dataHandler);
        return $this;
    }

    /**
     * Gets currently registered handlers data encoding/decoding
     *
     * This can be useful, to compress or seperate data storage
     * from the queue, in order to reduce memory usage
     *
     * @return \Pheanstalk\DataHandlerInterface $dataHandler[]
     */
    public function getList()
    {
        if (!$this->_handlers) {
            $this->_handlers = new \SplObjectStorage;
        }
        return $this->_handlers;
    }
}
