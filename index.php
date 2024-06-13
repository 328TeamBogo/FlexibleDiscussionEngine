<?php
require_once ("classes/post.php");
require_once ("classes/user.php");
require_once ("classes/admin.php");
require_once("vendor/autoload.php");
require_once ("controller/controller.php");
require_once("model/data-layer.php");
require_once("model/validate.php");
session_start();
/* Team BOGO
 * 11May2024
 * SDev 328
 */
// 328/FlexibleDiscussionEngine/index.php
// This is our fat free controller

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Require the autoloader


//connect to DB
require_once $_SERVER['DOCUMENT_ROOT'].'/../config.php';

try {
    // Instantiate our PDO Database Object
    $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    // Set the PDO error mode to exception
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    die( $e->getMessage() );
}

$TOPICS = array(
    "General",
    array( "General Discussion", "Memes", "Q&A"),
    "Computer Science",
    array( "Algorithms", "Data Structures", "Q&A"),
    "Mathematics",
    array("Numbers and Stuff")
);


// Instantiate the f3 base class
$f3 = Base::instance();
$controller = new Controller($f3);
$f3->set('TOPICS', $TOPICS);

//used to avoid file caching while still developing styles and/or JS
$f3->set('date', date('Y.m.d.H.i.s'));

// Define a default route
$f3->route('GET /', function()
    {
        $GLOBALS['controller']->home();
    }
);

// Dynamic addressing for chosen topic's discussion list.
$f3->route('GET /@topic', function ()
    {
        $GLOBALS['controller']->discussionsInTopic();
    }
);

//  Dynamic addressing for chosen discussion.
$f3->route('GET|POST /@topic/@discussion', function ()
    {
        $GLOBALS['controller']->postsInDiscussion();
    }
);

$f3->route('GET /@topic/@discussion/delete/@post', function ()
    {
        $GLOBALS['controller']->postDeletion();
    }
);

$f3->route('GET /@topic/@discussion/close', function ()
    {
        $GLOBALS['controller']->closeDiscussion();
    }
);

// Login Form Route
$f3->route('GET|POST /loginForm', function()
    {
       $GLOBALS['controller']->loginForm();
    }
);

// Sign Up Form Route
$f3->route('GET|POST /sign-up', function()
    {
        $GLOBALS['controller']->signupForm();
    }
);

$f3->route('GET /account-created', function ()
    {
        $GLOBALS['controller']->accountCreated();
    }
);

$f3->route('GET /logout', function ()
    {
        $GLOBALS['controller']->logOut();
    }
);

// Define a discussion-create route
$f3->route('GET|POST /@topic/discussion-create', function()
    {
        $GLOBALS['controller']->createDiscussion();
    }
);


// Run fat free
$f3->run();