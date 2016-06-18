<?php
require 'bootstrap.php';
use Cores\Route as Route;
use Cores\Database as Database;
use Cores\Hook as Hook;
use Cores\Models\Category as Category;


Hook::action('header', function () {
    echo 'This is header';
});


// Support to use Closure and environment variables or any additional arguments
$ev = 'This is Environment Variable';
Hook::action('body', function ($any, $eny) use ($ev) {
    echo '<p>This is header</p>';
    echo "<p>EV: $ev</p>";
    echo "<p>$any</p>";
    echo "<pre>" . print_r($eny, true) . "</pre>";
}, 10, 'This is additional arguments', [1, 2, 3]);

// Support Low/High Priority (Default priority is 10 likes WordPress)
Hook::action('body', function () {
    echo '<p>Three</p>';
}, 9);

Hook::action('body', function () {
    echo '<p>Two</p>';
}, 16);

Hook::action('body', function () {
    echo '<p>One</p>';
}, 5);

Hook::action('footer', function () {
    echo 'This is footer';
});

Route::get('/', function () {
    echo '
        <p>Demo</p>
        <p>' . Hook::doAction('header') . '</p>
        <div>' . Hook::doAction('body') . '</div>
        <p>' . Hook::doAction('footer') . '</p>
    ';
});

Route::dispatch();