<?php

/**
 * The class which controls all the site's navigation
 */
class Controller
{
    private $_f3;

    /**
     * Simple constructor
     *
     * @param $f3
     */
    function __construct($f3)
    {
        $this->_f3 = $f3;
    }

    /**
     * Navigation for the homepage
     */
    function home()
    {
        //Render view page.
        $view = new Template();
        echo $view->render("views/home-page.html");
    }

    /**
     * Navigation for the all discussions under a given topic.
     */
    function discussionsInTopic()
    {
        //to access topic $f3->get('PARAMS.topic');
        //use to pull discussion list.

        //Check topic exists
        //Might need to make topics a global array.
        $topic = $this->_f3->get('PARAMS.topic');
        //Retrieve discussions with SQL
        $result = retrieveDiscussionData($topic);


        $this->_f3->set('discussions', $result);

        $view = new Template();
        echo $view->render("views/discussion-list.html");
    }

    /**
     * Navigation for all posts under a given discussion
     */
    function postsInDiscussion()
    {
        // Initialize variables
        $message = "";

        // If the form has been posted
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            $message = $_POST['message'];

            $_SESSION['user']->createPost($this->_f3->get('PARAMS.discussion'), $message);
        }

        $discussionID = $this->_f3->get('PARAMS.discussion');
        retrievePostData($this->_f3, $discussionID);

        $view = new Template();
        echo $view->render("views/discussion.html");
    }

    /**
     * Navigation for the login form.
     */
    function loginForm()
    {
        if ($this->_f3->get('VERB') == 'POST') {
            $username = $this->_f3->get('POST.username');
            $password = $this->_f3->get('POST.password');
            $user = getUserData($username);
            // Store in SESSION
            if ($user && password_verify($password, $user['password'])) {
                if($user['status'] == 2) {
                    $_SESSION['user'] = new Admin($user['username'], $user['id'], $user['status']);
                }
                else {
                    $_SESSION['user'] = new User($user['username'], $user['id'], $user['status']);
                }
                $this->_f3->reroute('/');
            } else {
                $this->_f3->set('error', 'Invalid login');
            }
        }
        $view = new Template();
        echo $view->render('views/loginForm.html');
    }

    /**
     * Navigation for the signup form.
     */
    function signupForm()
    {
        if ($this->_f3->get('VERB') == 'POST') {
            $username = $this->_f3->get('POST.username');
            $password = $this->_f3->get('POST.password');
            $error = createUserData($username, $password);
            if($error == '') {
                $this->_f3->reroute('/account-created');
            }
            else {
                $this->_f3->set('error', $error);
            }
        }
        $view = new Template();
        echo $view->render('views/sign-up.html');
    }

    /**
     * Navigation for account creation
     */
    function accountCreated()
    {
        $view = new Template();
        echo $view->render('views/account-created.html');
    }

    /**
     * Function to handle logging out a user.
     */
    function logOut()
    {
        session_destroy();
        $this->_f3->reroute('/');
    }

    /**
     * Function for discussion creation.
     */
    function createDiscussion()
    {
        // If the form has been posted
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            $title = $_POST['title'];
            $message = $_POST['message'];

            $id = buildDiscussionData($title, $this->_f3->get('PARAMS.topic'));

            $_SESSION['user']->createPost($id, $message);

            $this->_f3->reroute(
                $this->_f3->get('PARAMS.topic')
            );
        }

        // Render a view page
        $view = new Template();
        echo $view->render('views/discussion-create.html');
    }

    /**
     * Function for post deletion.
     */
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

    /**
     * Function for closing a discussion
     */
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
        $this->_f3->reroute('/');
    }
}