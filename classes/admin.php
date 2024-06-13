<?php

/**
 * Admin class extending all things a user can do. Admins can also close
 * discussions.
 */
class Admin extends User
{
    /**
     * Function which handles closing a discussion.
     *
     * @param $discussionID
     */
    function archiveDiscussion($discussionID)
    {
        $sql = 'UPDATE discussions SET status = 0 WHERE id = :discussionID';
        $statement = $GLOBALS['dbh']->prepare($sql);
        $statement->bindParam(':discussionID', $discussionID);
        $statement->execute();
    }
}