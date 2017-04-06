<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pheanstalk;

/**
 *
 * @author stephan
 */
interface DataHandlerInterface {
    public function encode($data);
    public function decode($data);
    public function isAvailable(Job $job);
}
