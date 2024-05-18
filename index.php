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
//require_once("model/data-layer.php");
//require_once("model/validate.php");

// Instantiate the f3 base class
$f3 = Base::instance();

//used to avoid file caching while still developing styles and/or JS
$f3->set('date', date('Y.m.d.H.i.s'));

// Define a default route
$f3->route('GET /', function() {
    //echo '<h1>Hello world!</h1>';

    //Render view page.
    $view = new Template();
    echo $view->render("views/home-page.html");
});

// Dynamic addressing for chosen topic's discussion list.
$f3->route('GET /@topic', function () {
    //to access topic $f3->get('PARAMS.topic');
    //use to pull discussion list.

   $view = new Template();
   echo $view->render("views/topic-list.html");
});

//  Dynamic addressing for chosen discussion.
$f3->route('GET /@topic/@discussion', function () {
    //to access topic $f3->get('PARAMS.topic');
    //to access discussion $f3->get('PARAMS.discussion');
    //use to access list of posts.

    $view = new Template();
    echo $view->render("views/discussion.html");
});


// Login Form Route
$f3->route('GET /loginForm', function() {
    //echo '<h1>My Login Form</h1>';

    // Render a view page
    $view = new Template();
    echo $view->render('views/loginForm.html');
});

// Sign Up Form
$f3->route('GET /sign-up', function() {
    //echo '<h1>My signup</h1>';

    // Render a view page
    $view = new Template();
    echo $view->render('views/sign-up.html');
});

// Run fat free
$f3->run();