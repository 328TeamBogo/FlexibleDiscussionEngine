<?php

/**
 * Function to facilitate the creation of a discussion
 *
 * @param $title
 * @param $topic
 */
function createDiscussionData($title, $topic)
{
    //1. Define the query
    $sql = 'INSERT INTO discussions (topic, title) VALUES (:topic, :title)';

    //2. Prepare the statement
    $statement = $GLOBALS['dbh']->prepare($sql);

    //3. Bind the parameters
    $statement->bindParam(':title', $title);
    $statement->bindParam(':topic', $topic);

    //4. Execute the query
    $statement-> execute();

    //5. Process the result (if there is one)
    return $GLOBALS['dbh']->lastInsertId();
}

/**
 * Function to facilitate the construction of a user
 *
 * @param $username
 * @param $password
 */
function createUserData($username, $password)
{
    if (Validate::validUsername($username) && Validate::validPassword($password)) {
        if (!Validate::usernameExists($username, $GLOBALS['dbh'])) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = 'INSERT INTO users (username, password, email) VALUES (:username, :password, "placeholder@noemail.com")';
            $statement = $GLOBALS['dbh']->prepare($sql);
            $statement->bindParam(':username', $username);
            $statement->bindParam(':password', $hashedPassword);
            try {
                $statement->execute();
            } catch (PDOException $e) {
                return 'Sign-up failed: ' . $e->getMessage();
            }
        } else {
            return 'Username already exists';
        }
    } else {
        return 'Invalid username or password. Username must be at least 3 letters 
                                    and password must be at least 6 letters.';
    }
    return '';
}

/**
 * Function to retrieve user data
 *
 * @param $username
 */
function getUserData($username)
{
    $sql = 'SELECT * FROM users WHERE username = :username';
    $statement = $GLOBALS['dbh']->prepare($sql);
    $statement->bindParam(':username', $username);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}

/**
 * Function to retrieve posts within a discussion. All information is saved to
 * the fat-free hive. Posts are stored in an array.
 *
 * @param $f3
 * @param $discussionID
 */
function retrievePostData($f3, $discussionID)
{
    //Retrieve posts with SQL
    $sql = "SELECT posts.id, users.username, posts.user_id, posts.message, 
       posts.created_at, discussions.status, discussions.title
        FROM posts
        INNER JOIN discussions ON posts.discussion_id = discussions.id
        INNER JOIN users ON posts.user_id = users.id
        WHERE discussions.id = :discussionID AND posts.status = 1
        ORDER BY posts.created_at";
    $statement = $GLOBALS['dbh']->prepare($sql);
    $statement->bindParam(':discussionID', $discussionID, PDO::PARAM_INT);
    $statement->execute();

    $posts[] = array();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $index = 0;
    $discussionTitle = "";
    $firstUser = "";
    $active = true;
    foreach ($result as $row) {
        $posts[$index] = new Post($row['id'], $row['username'],
            $row['user_id'], $row['created_at'], $row['message']
        );
        $active = $row['status']; //would like to pull out of loop
        $discussionTitle = $row['title'];
        $firstUser = $row['username'];
        $index++;
    }
    //Test data
    /*$testPosts[] = array();
    for($i=0; $i<25; $i++) {
        $testPosts[$i] = (new Post("John$i"
            , "12/12/1989", "I was here!")
        );
    }*/

    //Assign posts to F3
    $f3->set("posts", $posts);
    $f3->set("activeDiscussion", $active);
    $f3->set("discussionTitle", $discussionTitle);
    $f3->set("firstUser", $firstUser);
}

/**
 * Function to retrieve all discussions in a given topic
 *
 * @param $topic
 */
function retrieveDiscussionData($topic)
{
    $sql = "SELECT * FROM discussions WHERE topic = :topic";
    $statement = $GLOBALS['dbh']->prepare($sql);
    $statement->bindParam(':topic', $topic);
    $statement->execute();

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}