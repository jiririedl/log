<?php
namespace Log\Writer\Manager;
/**
 * Relation database column log map
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @version 1.0
 * @package Log
 */
class RDBColumnMap
{
    /**
     * Log text column name
     * @var string
     */
    public $text = 'log';
    /**
     * Log level column name
     * @var string
     */
    public $level = 'id_log_level';
    /**
     * Log provider column name
     * @var string
     */
    public $provider = 'provider';
    /**
     * Log thread column name
     * @var string
     */
    public $thread = 'thread';
    /**
     * Log index column name
     * @var string
     */
    public $threadIndex = 'threadIndex';
    /**
     * Relation database column log map
     *
     * @param string|null $text text column name - if NULL is set, default value is used
     * @param string|null $level level column name - if NULL is set, default value is used
     * @param string|null $provider provider column name - if NULL is set, default value is used
     * @param string|null $thread thread column name - if NULL is set, default value is used
     * @param string|null $threadIndex thread index column name - if NULL is set, default value is used
     */
    public function __construct($text = NULL, $level = NULL, $provider = NULL, $thread = NULL, $threadIndex = NULL)
    {
        if(!is_null($text))
            $this->text = $text;
        if(!is_null($level))
            $this->level = $level;
        if(!is_null($provider))
            $this->provider = $provider;
        if(!is_null($thread))
            $this->thread = $thread;
        if(!is_null($threadIndex))
            $this->threadIndex = $threadIndex;
    }
}