<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});
*/
// Dynamically include all files in the routes directory
foreach (glob(__DIR__ . '/web/*.php') as $route_file) {
    require $route_file;
}


Route::get('{username}/buzz/{slug}', 'ProfileController@loadPostSingle');//singleview
Route::get('{username}/blog/{slug}', 'ProfileController@loadBlogSingle');
Route::get('{username}/question/{slug}', 'ProfileController@loadQuestionSingle');//singleview
//for ans : usrname/question/slug/answer/username
//Route::get('{username}/question/{slug}/answer/{ansusername}', 'ProfileController@loadAnswerSingle');//singleview
Route::get('{ansusername}/answer/{qslug}', 'ProfileController@loadAnswerSingle');//singleview

//Route::get('{username}/answer/{slug}', 'ProfileController@loadAnswerSingle');//singleview//TODO::remaining
/* this must be last placed here... dont change from here...*/
Route::get('{username}', array('as'=>'profile', 'uses'=>'ProfileController@showByUsername'));