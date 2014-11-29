<?php 
namespace Log\Writer\Manager;
/**
 * Object database collection interface
 *
 * interface used for writing logs into object databases
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @version 1.0
 * @package Log
 */
interface iOODB
{
    /**
     * Writes document in array into Object-oriented database
     *
     * @param array $data document in array
     * @return bool
     */
    public function writeArray(array $data);
}