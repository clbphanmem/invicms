<?php
require 'bootstrap.php';
use Cores\Route as Route;

Route::get('abc/{id}/get', function($id) { // Lỗi khi bỏ "get" vẫn chạy
    $test = 333;
    echo 'Hello World ' . $id;
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