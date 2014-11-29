<?php
namespace Log\Writer\Manager;
/**
 * Mongo collection manager for Log packager
 *
 * interface used for writing logs into object databases
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @version 1.0
 * @package Log
 */
class MongoCollection extends \Core\MongoCollectionManager implements iOODBManager
{
    /**
     * @see iOODBManager::writeArray()
     */
    function writeArray(array $data)
    {
        return (parent::writeArray($data) === true);
    }
}