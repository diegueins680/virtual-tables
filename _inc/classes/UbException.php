<?php

/**
 * @author Diego Saa <diego.saa@onlineacg.com>
 */

class UbException extends Exception
{

    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);

        $this->_log(__CLASS__ . ": [{$this->code}]: {$this->message}");
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    /**
     * Log message to DB
     * @param $msg STRING
     * @param $type STRING (Optional, default:'error', 'error','warning','info','debug')
     * @return UbException
     */
    private function _log($msg='', $type='error')
    {
        if( !defined('MYSQL_RW') && !MySQL::init(MySQL::$MYSQL_RW) )
        {
            @error_log($msg, 0);
        } else {
            $_file = str_replace('\\', '/', $this->file);
          //  @log2mysql($_file, $type, $msg);
        }
    }

}
