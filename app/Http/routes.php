<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'PostController@index');
Route::auth();
Route::get('home', 'HomeController@index');

// json responses
Route::post('get_cities', 'CityController@getCities');

Route::group(['middleware' => 'auth'], function(){
    Route::get('user/{username}', 'UserController@index');

    // personal functions
    route::post('user/change_avatar', 'UserController@changeAvatar');
    route::post('user/change_background', 'UserController@changeBackground');

    // json responses for auth users
    Route::post('check_is_user_your_friend', 'FriendsController@checkIsUserYourFriend');
    Route::post('send_invitation', 'FriendsController@sendInvitation');
    Route::post('accept_invitation', 'FriendsController@acceptInvitation');
    Route::post('delete_from_friends', 'FriendsController@deleteFromFriends');

    Route::post('get_notifications', 'NotificationController@getNotifications');
    Route::post('delete_notification', 'NotificationController@deleteNotification');

    Route::post('get_user_friends', 'FriendsController@getUserFriends');
    Route::post('get_user_by_id', 'UserController@getUserById');
    Route::post('get_user_basics_by_id', 'UserController@getUserBasicsById');

    // posts
    Route::post('get_users_posts', 'PostController@getUsersPosts');
    Route::post('get_user_posts', 'PostController@getUserPosts');

    Route::post('add_post', 'PostController@addPost');
    Route::post('delete_post', 'PostController@deletePost');

    Route::post('like_post', 'PostController@like');
    Route::post('unlike_post', 'PostController@unlike');

    // comments
    Route::post('get_post_comments', 'CommentController@getPostComments');

    Route::post('add_comment', 'CommentController@addComment');
    Route::post('delete_comment', 'CommentController@deleteComment');

    Route::post('like_comment', 'CommentController@like');
    Route::post('unlike_comment', 'CommentController@unlike');

    Route::post('extend_active_timestamp', 'UserController@extendActiveTimestamp');
    Route::post('is_user_active', 'UserController@isUserActive');
    Route::post('are_users_active', 'UserController@areUsersActive');

});