<?php
require 'bootstrap.php';
use Cores\Route as Route;
use Cores\Database as Database;
use Cores\Hook as Hook;
use Cores\Models\Category as Category;

Route::get('/', function () { // Lỗi khi bỏ "get" vẫn chạy
//    echo Category::name();

    Hook::action('header', function () {
        echo 'One2<br/>';
    });

    Hook::action('header', function () {
        echo 'Two5<br/>';
    }, 7);

    Hook::action('header', function () {
        echo 'Two3<br/>';
    }, 7);

    Hook::action('header', function () {
        echo 'Three<br/>';
    }, 9);

    Hook::action('header', function () {
        echo 'One1<br/>';
    });


//    echo dump(Hook::sortPriority(Hook::$actionArr['header']));
    echo '<p>------</p>';
    Hook::doAction('header');
    echo '<p>------</p>';
});

Route::get('abc/{id}/more', function($id) { // Lỗi khi bỏ "get" vẫn chạy
    echo 'More ' . $id;
});

Route::get('baby/{id}', 'TestController@index');

//Route::get('/', function() {
//    return 'OK Lar';
//});

//$route->get('abc/{id}/more', function($id) {
//    echo 'Baba World ' . $id;
//});
//
//
//$route->get('bbc', 'test');
//
//$route->get('/', function() {
//    echo 'Index';
//});

Route::dispatch();