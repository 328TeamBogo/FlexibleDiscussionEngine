<?php

class Post
{
    private $_username;
    private $_datetime;
    private $_message;

    /**
     * @param $_username
     * @param $_datetime
     * @param $_message
     */
    public function __construct($_username, $_datetime, $_message)
    {
        $this->_username = $_username;
        $this->_datetime = $_datetime;
        $this->_message = $_message;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->_username;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->_datetime;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->_message;
    }

}