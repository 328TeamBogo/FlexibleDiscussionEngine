<?php
session_start();
/* Eric Boyd
 * 5May2024
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

// Run fat free
$f3->run();