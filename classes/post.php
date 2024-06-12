<?php

class Post
{
    private $_postID;
    private $_username;
    private $_userID;
    private $_datetime;
    private $_message;

    /**
     * @param $_postID
     * @param $_username
     * @param $_userID
     * @param $_datetime
     * @param $_message
     */
    public function __construct($_postID, $_username, $_userID, $_datetime, $_message)
    {
        $this->_postID = $_postID;
        $this->_username = $_username;
        $this->_userID = $_userID;
        $this->_datetime = $_datetime;
        $this->_message = $_message;
    }

    /**
     * @return mixed
     */
    public function getPostID()
    {
        return $this->_postID;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->_username;
    }


    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->_userID;
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