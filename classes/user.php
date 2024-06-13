<?php
/**
 * User class handles all information which must be known about a user as well
 * as handling multiple actions a user can perform.
 */
class User
{
    private $_username;
    private $_userID;
    private $_permission;

    /**
     * @param $username
     * @param $userID
     * @param $permission
     */
    public function __construct($username, $userID, $permission)
    {
        $this->_username = $username;
        $this->_userID = $userID;
        $this->_permission = $permission;
    }

    /**
     * Getter for username
     *
     * @return mixed username
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * Getter for user ID
     *
     * @return mixed ID
     */
    public function getUserID()
    {
        return $this->_userID;
    }

    /**
     * Getter for user permissions
     * @return mixed permission level
     */
    public function getPermission()
    {
        return $this->_permission;
    }


    /**
     * Function to allow users to post to the forum;
     *
     * @param $discussion_id
     * @param $message
     */
    public function createPost($discussion_id, $message)
    {
        //1. Define the query
        $sql = 'INSERT INTO posts (discussion_id, message, user_id) VALUES (:discussion_id, :message, :user_id)';

        //2. Prepare the statement
        $statement = $GLOBALS['dbh']->prepare($sql);

        //2.5 Sanitize
        $message = preg_replace("/</", "&lt;", $message);
        $message = preg_replace("/>/", "&gt;", $message);

        //3. Bind the parameters
        $statement->bindParam(':message', $message);
        $statement->bindParam(':discussion_id', $discussion_id);
        $statement->bindParam(':user_id', $this->_userID);

        //4. Execute the query
        $statement-> execute();

        //5. Process the result (if there is one)
        //$id = $GLOBALS['dbh']->lastInsertId();
    }

    /**
     * Function to allow users to delete posts
     *
     * @param $postID
     */
    public function deletePost($postID)
    {
        if($this->_permission > 1)
        {
            $this->deletePostHelper($postID);
        }
        else
        {
            $sql = 'SELECT * FROM posts WHERE id = :postID';
            $statement = $GLOBALS['dbh']->prepare($sql);
            $statement->bindParam(':postID', $postID);
            $statement-> execute();
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            if($row['user_id'] == $this->_userID)
            {
                $this->deletePostHelper($postID);
            }
        }
    }

    private function deletePostHelper($postID)
    {
        $sql = 'UPDATE posts SET status = 0 WHERE id = :postID';
        $statement = $GLOBALS['dbh']->prepare($sql);
        $statement->bindParam(':postID', $postID);
        $statement-> execute();
    }
}