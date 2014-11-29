<?php
namespace Log\Writer\Manager;
use \UnexpectedValueException;
use \RuntimeException;
/**
 * MySQL table manager for Log packager
 *
 * interface used for writing logs into object databases
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @version 1.0
 * @package Log
 */
class MySQLiTable implements iRDBManager
{
    /**
     * Allows throwing exceptions
     * @var bool
     */
    public $allowExceptions = false;
    /**
     * MySQLi connection
     * @var \mysqli
     */
    protected $_databaseConnection = NULL;
    /**
     * Table name
     * @var string
     */
    protected $_tableName = NULL;
    /**
     * Column name map
     * @var RDBColumnMap
     */
    protected $_columnMap = NULL;

    /**
     * MySQL table manager
     *
     * @param \mysqli|NULL $connection
     * @param string|NULL $table
     * @param RDBColumnMap|NULL $columnMap if NULL default map is used
     */
    public function __construct(\mysqli $connection = NULL, $table = NULL, RDBColumnMap $columnMap)
    {
        if(!is_null($connection))
            $this->setDatabaseConnection($connection);

        if(!is_null($table))
            $this->setTableName($table);

        if(is_null($columnMap))
            $columnMap = new RDBColumnMap();

        $this->setColumnMap($columnMap);
    }
    /**
     * @see iRDBManager::writeLog()
     * @throws RuntimeException Exceptions are thrown only if allowExceptions is set to TRUE
     */
    public function writeLog($logText, $logLevel,$provider, $logThread, $logIndex)
    {
        try
        {
            $stmtQuery = 'INSERT INTO '.$this->_getTableName().'
                        ('.$this->_getColumnMap()->text.', '.$this->_getColumnMap()->level.', '.$this->_getColumnMap()->provider.', '.$this->_getColumnMap()->thread.', '.$this->_getColumnMap()->threadIndex.')
                        (?, ?, ?, ?, ?)';
            $stmt = $this->_getDatabaseConnection()->prepare($stmtQuery);
            $stmt->bind_param('sissi', $logText, $logLevel, $provider, $logThread, $logIndex);
            $result = $stmt->execute();
            $stmt->close();
        }catch (\Exception $e)
        {
            if($this->allowExceptions)
                throw $e;
            return false;
        }

        if($this->allowExceptions && !$result)
            throw new RuntimeException('Database error: ['.$stmt->errno.'] '.$stmt->error);

        return $result;
    }
    /**
     * Sets database connection
     * @param \mysqli $connection
     */
    public function setDatabaseConnection(\mysqli $connection)
    {
        $this->_databaseConnection = $connection;
    }
    /**
     * Sets table name
     *
     * @param string $table
     * @throws UnexpectedValueException Exceptions are thrown only if allowExceptions is set to TRUE
     * @return bool
     */
    public function setTableName($table)
    {
        if(!is_string($table))
        {
            if($this->allowExceptions)
                throw new UnexpectedValueException('String is expected as table name.',1);

            return false;
        }
        $this->_tableName = $table;
        return true;
    }
    /**
     * Sets log columns maps
     *
     * @param RDBColumnMap $columnMap
     */
    public function setColumnMap(RDBColumnMap $columnMap)
    {
        $this->_columnMap = $columnMap;
    }
    /**
     * Returns database connection
     *
     * @throws UnexpectedValueException
     * @return \mysqli
     */
    protected function _getDatabaseConnection()
    {
        if(is_null($this->_databaseConnection))
            throw new UnexpectedValueException('There is no database connection set - use setDatabaseConnection() first!',2);

        return $this->_databaseConnection;
    }
    /**
     * Returns table name
     *
     * @throws UnexpectedValueException
     * @return string
     */
    protected function _getTableName()
    {
        if(is_null($this->_tableName))
            throw new UnexpectedValueException('There is no table name set - use setTableName() first!',3);

        return $this->_tableName;
    }
    /**
     * Returns columns map
     *
     * @throws UnexpectedValueException
     * @return RDBColumnMap
     */
    protected function _getColumnMap()
    {
        if(is_null($this->_columnMap))
            throw new UnexpectedValueException('There is no column map set - use setColumnMap() first!',4);

        return $this->_columnMap;
    }
}