<?php
namespace Log\Writer;
use Log\iLogable;
/**
 * Log writers 
 * 
 * Log writers are used by log object. You can make a new writers for mail, or database f.e.
 *
 * @author Jiří Riedl <jiri.riedl@mobilbonus.cz>
 * @package Log
 * @version 1.0
 */
interface iWriter
{
	/**
	 * Write text to log
	 * 
	 * @param string $logText
	 * @param number $logLevel
	 * @param string $provider
	 * @param string $logThread
	 * @param number $logIndex
     *
     * @return bool
	 */
	public function writeText($logText, $logLevel,$provider, $logThread, $logIndex);
	/**
	 * Write object to log
	 * 
	 * @param iLogable $object
	 * @param number $logLevel
	 * @param string $provider
	 * @param string $logThread
	 * @param number $logIndex
     *
     * @return bool
	 */
	public function writeObject(iLogable $object, $logLevel,$provider, $logThread, $logIndex);
}