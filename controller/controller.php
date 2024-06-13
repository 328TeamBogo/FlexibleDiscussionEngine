<?php

class Controller
{
    private $_f3;

    function __construct($f3)
    {
        $this->_f3 = $f3;
    }

    function home()
    {
        //Render view page.
        $view = new Template();
        echo $view->render("views/home-page.html");
    }

    function discussionsInTopic()
    {
        //to access topic $f3->get('PARAMS.topic');
        //use to pull discussion list.

        //Check topic exists
        //Might need to make topics a global array.

        //Retrieve discussions with SQL
        $sql = "SELECT * FROM discussions WHERE topic = :topic";
        $statement = $GLOBALS['dbh']->prepare($sql);
        $topic = $this->_f3->get('PARAMS.topic');
        $statement->bindParam(':topic', $topic);
        $statement->execute();

        $discussions[] = array();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        //Implement SQL pull of discussions
        /*$testDiscussions = ['Radix Sort', 'Merge Sort', 'Bogosort',
            'Bubble Sort', 'Quicksort', 'Heapsort', 'Timsort'];*/

        $this->_f3->set('discussions', $result);

        $view = new Template();
        echo $view->render("views/discussion-list.html");
    }

    function postsInDiscussion()
    {
        // Initialize variables
        $message = "";

        // If the form has been posted
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            $message = $_POST['message'];

            $_SESSION['user']->createPost($this->_f3->get('PARAMS.discussion'), $message);
        }


        //to access topic $f3->get('PARAMS.topic');
        //to access discussion $f3->get('PARAMS.discussion');
        //use to access list of posts.

        //Check discussion exists

        //Retrieve posts with SQL
        $sql = "SELECT posts.id, users.username, posts.user_id, posts.message, 
       posts.created_at, discussions.status, discussions.title
        FROM posts
        INNER JOIN discussions ON posts.discussion_id = discussions.id
        INNER JOIN users ON posts.user_id = users.id
        WHERE discussions.id = :discussionID AND posts.status = 1
        ORDER BY posts.created_at";
        $statement = $GLOBALS['dbh']->prepare($sql);
        $discussionID = $this->_f3->get('PARAMS.discussion');
        $statement->bindParam(':discussionID', $discussionID, PDO::PARAM_INT);
        $statement->execute();

        $posts[] = array();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $index = 0;
        $discussionTitle = "";
        $active = true;
        $firstUser = "";
        foreach ($result as $row) {
            $posts[$index] = new Post($row['id'], $row['username'],
                $row['user_id'], $row['created_at'], $row['message']
            );
            if ($index == 0) {
                $discussionTitle = $row['title'];
                $active = $row['status'];
                $firstUser = $row['username'];
            }
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
        $this->_f3->set("posts", $posts);
        $this->_f3->set("activeDiscussion", $active);
        $this->_f3->set("discussionTitle", $discussionTitle);
        $this->_f3->set("firstUser", $firstUser);

        $view = new Template();
        echo $view->render("views/discussion.html");
    }

    function loginForm()
    {
        if ($this->_f3->get('VERB') == 'POST') {
            $username = $this->_f3->get('POST.username');
            $password = $this->_f3->get('POST.password');

            $sql = 'SELECT * FROM users WHERE username = :username';
            $statement = $GLOBALS['dbh']->prepare($sql);
            $statement->bindParam(':username', $username);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            // Store in SESSION
            if ($user && password_verify($password, $user['password']))
            {
                if($user['status'] == 2)
                {
                    $_SESSION['user'] = new Admin($user['username'], $user['id'], $user['status']);
                }
                else
                {
                    $_SESSION['user'] = new User($user['username'], $user['id'], $user['status']);
                }
                $this->_f3->reroute('/');
            } else {
                $this->_f3->set('error', 'Invalid login');
            }
        }
        $view = new Template();
        echo $view->render('views/loginForm.html');
        ///////////////////////////////////////////////////////////////////////////////

        // few typos and above is fixed code - Reshad
        /*        if ($f3->get('VERB') == 'POST') {
                    $username = $f3->get('POST.username');
                    $password = $f3->get('POST.password');

        //            $sql = 'SELECT password FROM users WHERE username = :username';
        //            $statement = $GLOBALS['dbh']->prepare($sql);
        //            $statement->bindParam(':username', $username);
        //            $statement->execute();
        //            $hash = $statement->fetchAll(PDO::FETCH_ASSOC); //might be wrong fetch
        //
        //            // Store in SESSION
        //            if (password_verify($password, $hash['password'])) {
        //                $_SESSION['username'] = $username;
        //                $f3->reroute('/');
        //            } else {
        //                $f3->set('error', 'Invalid login');
        //            }

                    // Store in SESSION
                    if ($username && $password) {
                        $_SESSION['username'] = $username;
                        $f3->reroute('/');
                    } else {
                        $f3->set('error', 'Invalid login');
                    }
                } else {
                    $view = new Template();
                    echo $view->render('views/loginForm.html');
                }*/
    }

    function signupForm()
    {
        if ($this->_f3->get('VERB') == 'POST') {
            $username = $this->_f3->get('POST.username');
            $password = $this->_f3->get('POST.password');

            if (Validate::validUsername($username) && Validate::validPassword($password)) {
                if (!Validate::usernameExists($username, $GLOBALS['dbh'])) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $sql = 'INSERT INTO users (username, password, email) VALUES (:username, :password, "placeholder@noemail.com")';
                    $statement = $GLOBALS['dbh']->prepare($sql);
                    $statement->bindParam(':username', $username);
                    $statement->bindParam(':password', $hashedPassword);
                    try {
                        $statement->execute();
                        $this->_f3->reroute('/account-created');
                    } catch (PDOException $e) {
                        $this->_f3->set('error', 'Sign-up failed: ' . $e->getMessage());
                    }
                } else {
                    $this->_f3->set('error', 'Username already exists');
                }
            } else {
                $this->_f3->set('error', 'Invalid username or password. Username must be at least 3 letters 
                                    and password must be at least 6 letters.');
            }
        }
        $view = new Template();
        echo $view->render('views/sign-up.html');
    }

    function accountCreated()
    {
        $view = new Template();
        echo $view->render('views/account-created.html');
    }

    function logOut()
    {
        session_destroy();
        $this->_f3->reroute('/');
    }

    function createDiscussion()
    {
        // If the form has been posted
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            $title = $_POST['title'];
            $message = $_POST['message'];

            //1. Define the query
            $sql = 'INSERT INTO discussions (topic, title) VALUES (:topic, :title)';

            //2. Prepare the statement
            $statement = $GLOBALS['dbh']->prepare($sql);

            //3. Bind the parameters
            $statement->bindParam(':title', $title);
            $statement->bindParam(':topic', $this->_f3->get('PARAMS.topic'));

            //4. Execute the query
            $statement-> execute();

            //5. Process the result (if there is one)
            $id = $GLOBALS['dbh']->lastInsertId();

            $_SESSION['user']->createPost($id, $message);

            $this->_f3->reroute(
                $this->_f3->get('PARAMS.topic')
            );
        }

        // Render a view page
        $view = new Template();
        echo $view->render('views/discussion-create.html');
    }

    function postDeletion()
    {
        if(isset($_SESSION['user']))
        {
            $_SESSION['user']->deletePost($this->_f3->get('PARAMS.post'));
            $this->_f3->reroute(
                $this->_f3->get('PARAMS.topic').'/'.
                $this->_f3->get('PARAMS.discussion')
            );
        }
        else
        {
            $this->_f3->reroute('/');
        }
    }

    function closeDiscussion()
    {
        if(isset($_SESSION['user']))
        {
            if($_SESSION['user'] instanceof Admin)
            {
                $_SESSION['user']->archiveDiscussion($this->_f3->get('PARAMS.discussion'));
                $this->_f3->reroute(
                    $this->_f3->get('PARAMS.topic').'/'.
                    $this->_f3->get('PARAMS.discussion')
                );
            }
        }
        $result = ($_SESSION['user'] instanceof Admin);
        var_dump($result);
        //$this->_f3->reroute('/');
    }
}