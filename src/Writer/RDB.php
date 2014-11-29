<?php
namespace Log\Writer;
use Log\Writer\Manager\iRDB;
use Log\iLogable;
use \UnexpectedValueException;
/**
 * Log to Relation database writer
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @version 1.0
 * @package Log
 */
class RDB implements iWriter
{
    /**
     * Allows throwing exceptions during writing log
     * @var bool
     */
    public $allowExceptions = false;
	/**
	 * RDB manager
	 * @var iRDB
	 */
	protected $_RDBManager = NULL;
    /**
     * Relation database log writer
     *
     * @param iRDB|NULL $RDBManager
     */
	public function __construct(iRDB $RDBManager = NULL)
	{
        if(!is_null($RDBManager))
		    $this->setRDBManager($RDBManager);
	}
    /**
     * @see iWriter::writeText()
     * @throws UnexpectedValueException Exceptions are thrown only if allowExceptions is set to TRUE
     */
    public function writeText($logText, $logLevel,$provider, $logThread, $logIndex)
    {
       try
       {
            $result = $this->_getRDBManager()->writeLog($logText,$logLevel,$provider,$logThread,$logIndex);
       }catch(\Exception $e)
       {
           if($this->allowExceptions)
               throw $e;
           else
               return false;
       }
       return $result;
    }
    /**
     * @see iWriter::writeObject()
     * @throws UnexpectedValueException Exceptions are thrown only if allowExceptions is set to TRUE
     */
    public function writeObject(iLogable $object, $logLevel,$provider, $logThread, $logIndex)
    {
        return $this->writeText($object->getLogString(), $logLevel, $provider,$logThread, $logIndex);
    }
    /**
     * Sets relation database manager
     *
     * @param iRDB $RDBManager
     */
    public function setRDBManager(iRDB $RDBManager)
    {
        $this->_RDBManager = $RDBManager;
    }
    /**
     * Returns relation database manager
     *
     * @throws UnexpectedValueException
     * @return iRDB
     */
    protected function _getRDBManager()
    {
        if(is_null($this->_RDBManager))
            throw new UnexpectedValueException('There is no RDBManager set - use setRDBManager() first!');

        return $this->_RDBManager;
    }
}