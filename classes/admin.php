<?php
class Admin extends User
{
    function archiveDiscussion($discussionID)
    {
        $sql = 'UPDATE discussions SET status = 0 WHERE id = :discussionID';
        $statement = $GLOBALS['dbh']->prepare($sql);
        $statement->bindParam(':discussionID', $discussionID);
        $statement->execute();
    }
}