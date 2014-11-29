<?php
namespace Log;
/**
 * Logable object interface
 * 
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @version 1.0
 * @package Log
 */
interface iLogable
{
	/**
	 * Translate object variables to array for logging
	 * 
	 * @return array
	 */
	public function getLogArray();
	/**
	 * Translate object variables and object state into string
	 * 
	 * @return string
	 */
	public function getLogString();
}