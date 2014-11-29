<?php 
namespace Log\Writer;
use Log\iLogable;
/**
 * Log file writer
 *
 * logWriter for files, used for writing log into text files
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @version 1.0
 * @package Log
 */
class File implements iWriter
{
    /**
     * Log file full path
     *
     * @var null|string
     */
    private $_fileName = NULL;
    /**
     * Opened file handler
     *
     * @var null|resource
     */
    private $_file = NULL;

    /**
     * Log file full path
     *
     * @param null|string $fileName
     */
    public function __construct($fileName = NULL)
	{
		$this->setFile($fileName);
	}
	/**
	 * Sets a file
	 *
	 * Set name of file for writing
	 * @param string $fileName
	 */
	public function setFile($fileName)
	{
		$this->_fileName = $fileName;
	}
	/**
	 * @see iWriter::writeText()
	 */
	public function writeText($logText, $logLevel,$provider, $logThread, $logIndex)
	{
		if($this->_openFile())
		{
			$result = fwrite($this->_file,$this->_logTextBuild($logText, $logLevel, $provider,$logThread, $logIndex)."\r\n");
			@fclose($this->_file);
            return ($result !== false);
		}
        return false;
	}
	/**
	 * @see iWriter::writeObject()
	 */
	public function writeObject(iLogable $object, $logLevel, $provider, $logThread, $logIndex)
	{
		return $this->writeText($object->getLogString(), $logLevel, $provider,$logThread, $logIndex);
	}
	/**
	 * Open file
	 *
	 * Try to open file ($this->_fileName)
	 * @return boolean returns TRUE if file is open
	 */
	private function _openFile()
	{
		$this->_file = @fopen($this->_fileName, 'a');
		return ($this->_file !== false);
	}
	/**
	 * Generate a log string
     *
	 * @param string $logText
	 * @param number $logLevel
	 * @param string $provider
	 * @param string $logThread
	 * @param number $logIndex
	 * @return string
	 */
	private function _logTextBuild($logText,$logLevel,$provider, $logThread, $logIndex)
	{
		$text = $logText;
		switch ($logLevel)
		{
			case Log::debug:	$text = 'DEBUG: '.$text; 
								break;
			case Log::error:	$text = 'ERROR: '.$text;
								break;
			case Log::warning:	$text = 'WARNING: '.$text;
								break;
			case Log::notice:	$text = 'NOTICE: '.$text;
								break;
			case Log::userActivity: $text = 'USER: '.$text;
								break;
			case Log::critical: $text = 'CRITICAL: '.$text;
								break;
		}
		@date_default_timezone_set('Europe/Prague');
		return @date("Y-m-d H:i:s> ").'['.$provider.']{'.$logThread.':'.$logIndex.'}'.$text;	
	}
}