<?php
namespace Log\Writer;
use Log\iLogable;
/**
 * Log Mail Writer
 *
 * Email log writer
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @version 1.0
 * @package Log
 */
class Mail implements iWriter
{
	/**
	 * Email recipients
	 * @var string[] 
	 */
	protected $_mailAddress = array();
	/**
	 * Sending Headers
	 * @var string[] 
	 */
	protected $_headers = array();
	/**
	 * Init mail log Writer
	 * @param array $mailAddresses
	 * @param array $headers
	 */
	public function __construct($mailAddresses = array(), $headers = array())
    {
        /** Default Header mime version and charset codding	 */
        $this->_setDefaultHeader();

        foreach ($mailAddresses as $mailAddress)
			$this->addMailAddress($mailAddress);

		foreach ($headers as $parameter=>$value)
        {
            $this->addHeader($parameter,$value);
        }
	}
	/**
	 * Adding e-mail address
	 * @param string $mailAddress
	 */
	public function addMailAddress($mailAddress)
	{
        if(!in_array($mailAddress, $this->_mailAddress))
            $this->_mailAddress[] = $mailAddress;
	}
	/**
	 * Returns mail addresses
	 * @return string
	 */
	protected function _getMailAddresses()
	{
		return $this->_mailAddress;
	}
	/**
	 * Add e-mail header
     *
     * if parameter already exists will be value replaced
     *
	 * @param string $parameter
     * @param string $value
	 */
	public function addHeader($parameter, $value)
	{
		$this->_headers[$parameter] = $value;
	}
	/**
	 * Getting sending headers
	 * @return string
	 */
	protected function _getHeader()
	{
		$header = array();
        foreach ($this->_headers as $parameter=>$value)
            $header[] = $parameter.': '.$value;

        return $header;
	}
	/**
	 * Sets default headers
	 */
	protected function _setDefaultHeader()
	{
		$this->addHeader('MIME-Version', '1.0');
        $this->addHeader('Content-type', 'text/plain; charset=utf-8');
	}
	/**
	 * @see iWriter::writeText()
	 */
	public function writeText($logText, $logLevel, $provider, $logThread, $logIndex)
	{
		return $this->_send($this->_logTextBuild($logText, $logLevel, $provider, $logThread, $logIndex), $this->_logSubjectBuild($logText, $logLevel, $provider));
	}
	/**
	 * @see iWriter::writeObject()
	 */
	public function writeObject(iLogable $object, $logLevel,$provider,$logThread, $logIndex)
	{
		return $this->writeText($object->getLogString(), $logLevel, $provider,$logThread, $logIndex);
	}
	/**
	 * Try to sent email with parameters
     *
	 * @param string $text
	 * @param string $subject
	 * @return boolean
	 */
	protected function _send($text,$subject)
	{
		$addresses = \implode(",", $this->_getMailAddresses());
		
		/** Sending mail to recipients */
		if(!\mail($addresses, $subject, $text, \implode("\r\n", $this->_getHeader())))
			return false;
		return true;
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
		$text = $this->_getLogLevel($logLevel).$logText;
		@date_default_timezone_set('Europe/Prague');
		$text .= "\r\n".'Thread ID: '.$logThread;
		$text .= "\r\n".'Log index: '.$logIndex;
		$text .= "\r\n".'Environment: '.\Core\Environment::get()->name();
		$text .= "\r\n".'Server: '.var_export($_SERVER,1);
		$text .= "\r\n".'Request: '.var_export($_REQUEST,1);
		return @date("Y-m-d H:i:s> ").'['.$provider.'] '.$text;	
	}
	/**
	 * Generate a subject string
     *
	 * @param string $logText
	 * @param number $logLevel
	 * @param string $provider
     * @return string
	 */
	private function _logSubjectBuild($logText,$logLevel,$provider)
	{
		$text = '{'.\Core\Environment::get()->name().'} '.$this->_getLogLevel($logLevel).'['.$provider.']';
		return $text;
	}
	/**
	 * Returns string by log level
	 * @param number $logLevel
	 * @return string
	 */
	private function _getLogLevel($logLevel)
	{
		switch ($logLevel)
		{
			case Log::debug:	$text = 'DEBUG: ';
								break;
			case Log::error:	$text = 'ERROR: ';
								break;
			case Log::warning:	$text = 'WARNING: ';
								break;
			case Log::notice:	$text = 'NOTICE: ';
								break;
			case Log::userActivity: $text = 'USER: ';
								break;
			case Log::critical: $text = 'CRITICAL: ';
								break;
			case Log::webError: $text = 'ERROR FROM FUCKING WEB: ';
								break;
			default:
					$text = 'Undefined: ';
					break;
		}
		return $text;
	}
}
