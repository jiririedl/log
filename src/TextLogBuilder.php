<?php
namespace Log;
/**
 * Text log builder
 *
 * Builds text log, allows modify string for logging
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @version 1.0
 * @package Log
 */
class TextLogBuilder
{
    /**
     * Build function
     * @var callable|null
     */
    protected $_buildFunction = NULL;

    /**
     * Text log builder
     *
     * Builds text log, allows modify string for logging
     *
     * @param callable|NULL $buildFunction function have to receive arguments : $logText, $logLevel,$provider, $logThread, $logIndex and return string
     */
    public function __construct(callable $buildFunction = NULL)
    {
        if(is_null($buildFunction))
        {
            $buildFunction = function($logText, $logLevel,$provider, $logThread, $logIndex)
                            {
                                return date('Y-m-d H:i:s').' ['.$provider.'] '.$logLevel.': '.$logText.' {'.$logThread.'::'.$logIndex.'}';
                            };
        }
        $this->setBuildFunction($buildFunction);
    }
    /**
     * Builds string for logging
     *
     * @param string $logText
     * @param number $logLevel
     * @param string $provider
     * @param string $logThread
     * @param number $logIndex
     *
     * @return string
     */
    public function getString($logText, $logLevel,$provider, $logThread, $logIndex)
    {
        $function = $this->_getBuildFunction();
        return $function($logText, $logLevel,$provider, $logThread, $logIndex);
    }
    /**
     * Sets log build function
     *
     * function have to receive arguments : $logText, $logLevel,$provider, $logThread, $logIndex and return string
     *
     * @param callable $function
     */
    public function setBuildFunction(callable $function)
    {
        $this->_buildFunction = $function;
    }
    /**
     * Returns build function
     *
     * @return callable
     */
    protected function _getBuildFunction()
    {
        return $this->_buildFunction;
    }
    /**
     * Create PrintF pattern closure
     *
     * builds closure with sprintf() and given pattern
     *
     * @example createPrintFPatternClosure('%s %d %s %s %d')
     *
     * @param string $pattern pattern for printf() function
     * @return callable
     */
    public static function createPrintFPatternClosure($pattern)
    {
        $function = function($logText, $logLevel,$provider, $logThread, $logIndex) use ($pattern)
                    {
                        return sprintf($pattern, $logText, $logLevel,$provider, $logThread, $logIndex);
                    };
        return $function;
    }
}