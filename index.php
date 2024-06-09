<?php
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
require_once("vendor/autoload.php");
require_once ("classes/post.php");
//require_once("model/data-layer.php");
//require_once("model/validate.php");

//connect to DB
require_once $_SERVER['DOCUMENT_ROOT'].'/../config.php';

try {
    // Instantiate our PDO Database Object
    $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    echo 'Connected to database!';
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

$f3->set('TOPICS', $TOPICS);

//used to avoid file caching while still developing styles and/or JS
$f3->set('date', date('Y.m.d.H.i.s'));

// Define a default route
$f3->route('GET /', function()
    {
        //echo '<h1>Hello world!</h1>';

        //Render view page.
        $view = new Template();
        echo $view->render("views/home-page.html");
    }
);

// Dynamic addressing for chosen topic's discussion list.
$f3->route('GET /@topic', function ($f3)
    {
        //to access topic $f3->get('PARAMS.topic');
        //use to pull discussion list.

        //Check topic exists
        //Might need to make topics a global array.

        //Retrieve discussions with SQL
        $sql = "SELECT * FROM discussions WHERE topic = :topic";
        $statement = $GLOBALS['dbh']->prepare($sql);
        $topic = $f3->get('PARAMS.topic');
        $statement->bindParam(':topic', $topic);
        $statement->execute();

        $discussions[] = array();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        //Implement SQL pull of discussions
        /*$testDiscussions = ['Radix Sort', 'Merge Sort', 'Bogosort',
            'Bubble Sort', 'Quicksort', 'Heapsort', 'Timsort'];*/

        $f3->set('discussions', $result);

       $view = new Template();
       echo $view->render("views/discussion-list.html");
    }
);

//  Dynamic addressing for chosen discussion.
$f3->route('GET|POST /@topic/@discussion', function ($f3)
    {
        // Initialize variables
        $message = "";

        // If the form has been posted
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            $message = $_POST['message'];

            createPost($f3->get('PARAMS.discussion'), $message, 1);
        }


        //to access topic $f3->get('PARAMS.topic');
        //to access discussion $f3->get('PARAMS.discussion');
        //use to access list of posts.

        //Check discussion exists

        //Retrieve posts with SQL
        $sql = "SELECT users.username, posts.message, posts.created_at
        FROM posts
        INNER JOIN discussions ON posts.discussion_id = discussions.id
        INNER JOIN users ON posts.user_id = users.id
        WHERE discussions.id = :discussionID
        ORDER BY posts.created_at";
        $statement = $GLOBALS['dbh']->prepare($sql);
        $discussionID = $f3->get('PARAMS.discussion');
        $statement->bindParam(':discussionID', $discussionID, PDO::PARAM_INT);
        $statement->execute();

        $posts[] = array();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        echo $GLOBALS['dbh']->errorCode();
        $index = 0;
        foreach ($result as $row) {
            $posts[$index] = new Post($row['username'],
             $row['created_at'], $row['message']
             );
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

        $view = new Template();
        echo $view->render("views/discussion.html");
    }
);

// Login Form Route
$f3->route('GET|POST /loginForm', function($f3)
    {

        if ($f3->get('VERB') == 'POST') {
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
        }
    }
);

// Sign Up Form Route
$f3->route('GET|POST /sign-up', function($f3)
    {
        if ($f3->get('VERB') == 'POST') {
            $username = $f3->get('POST.username');
            $password = $f3->get('POST.password');

            // Store in SESSION
            if ($username && $password) {
                $_SESSION['username'] = $username;
                $f3->reroute('/');
            } else {
                $f3->set('error', 'Sign-up failed');
            }
        } else {
            $view = new Template();
            echo $view->render('views/sign-up.html');
        }
    }
);

// Define a discussion-create route
$f3->route('GET|POST /@topic/discussion-create', function($f3) {
    //echo '<h1>Testing!</h1>';

    // Initialize variables
    $title = "";

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
        $statement->bindParam(':topic', $f3->get('PARAMS.topic'));

        //4. Execute the query
        $statement-> execute();

        //5. Process the result (if there is one)
        $id = $GLOBALS['dbh']->lastInsertId();
        echo "<p>Topic $id was inserted successfully</p>";

        createPost($id, $message, 1);
    }

    // Render a view page
    $view = new Template();
    echo $view->render('views/discussion-create.html');
});

// Run fat free
$f3->run();

function createPost ($discussion_id, $message, $user_id) {
    //1. Define the query
    $sql = 'INSERT INTO posts (discussion_id, message, user_id) VALUES (:discussion_id, :message, :user_id)';

    //2. Prepare the statement
    $statement = $GLOBALS['dbh']->prepare($sql);

    //3. Bind the parameters
    $statement->bindParam(':message', $message);
    $statement->bindParam(':discussion_id', $discussion_id);
    $statement->bindParam(':user_id', $user_id);

    //4. Execute the query
    $statement-> execute();

    //5. Process the result (if there is one)
    $id = $GLOBALS['dbh']->lastInsertId();
    echo "<p>Topic $id was inserted successfully</p>";
}