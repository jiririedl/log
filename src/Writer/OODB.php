<?php
namespace Log\Writer;
use Log\Writer\Manager\iOODB;
use Log\iLogable;
/**
 * Log to Object Oriented Databases writer
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @version 1.0
 * @package Log
 */
class OODB implements iWriter
{
	/**
	 * OODB manager
     *
	 * @var iOODB
	 */
	protected $_OODBManager = NULL;

    /**
     * Object Oriented Databases writer
     *
     * @param iOODB $OODBManager
     */
	public function __construct(iOODB $OODBManager)
	{
		$this->_OODBManager = $OODBManager;
	}
	/**
	 * @see iWriter::writeText()
	 */
	public function writeText($logText, $logLevel,$provider, $logThread, $logIndex)
	{
		@date_default_timezone_set('Europe/Prague');
		$log = array('Log'=>$logText, 'Level'=>$logLevel, 'Provider' => $provider,'Time'=>@date("Y-m-d H:i:s"), 'Thread'=>$logThread, 'Index'=>$logIndex);
		return $this->_OODBManager->writeArray($log);
	}
	/**
	 * @see iWriter::writeObject()
	 */
	public function writeObject(iLogable $object, $logLevel,$provider, $logThread, $logIndex)
	{
		@date_default_timezone_set('Europe/Prague');
		$log = array('Log'=>$object->getLogArray(), 'Level'=>$logLevel, 'Provider' => $provider,'Time'=>@date("Y-m-d H:i:s"), 'Thread'=>$logThread, 'Index'=>$logIndex);
		return $this->_OODBManager->writeArray($log);
	}
}