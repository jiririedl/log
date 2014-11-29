<?php
namespace Log\Writer\Manager;
/**
 * Relation database interface
 *
 * interface used for writing logs into relation databases
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @version 1.0
 * @package Log
 */
interface iRDB
{
    /**
     * Writes document in array into Object-oriented database
     *
     * @param string $logText
     * @param number $logLevel
     * @param string $provider
     * @param string $logThread
     * @param number $logIndex
     * @return bool
     */
    public function writeLog($logText, $logLevel,$provider, $logThread, $logIndex);
}