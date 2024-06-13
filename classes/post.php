<?php

/**
 * Post class handles the storage and retrieval of all information about a
 * post.
 */
class Post
{
    private $_postID;
    private $_username;
    private $_userID;
    private $_datetime;
    private $_message;

    /**
     * Basic constructor for a post
     *
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
     * Simple getter for post ID
     */
    public function getPostID()
    {
        return $this->_postID;
    }

    /**
     * Simple getter for post's user's name
     */
    public function getUsername()
    {
        return $this->_username;
    }


    /**
     * Simple getter for post's user's ID
     */
    public function getUserID()
    {
        return $this->_userID;
    }

    /**
     * Simple getter for date and time of the post
     */
    public function getDate()
    {
        return $this->_datetime;
    }

    /**
     * Simple getter for body of the post
     */
    public function getMessage()
    {
        return $this->_message;
    }
}