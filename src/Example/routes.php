<?php

Route::group(['prefix'=>'laravel-talk/example', 'middleware'=>'web', 'namespace'=>'Nahid\Talk\Example'], function() {
	Route::get('message/inbox', 'TalkController@inbox');
	Route::get('message/read/{id}', 'TalkController@readMessage');
	Route::post('message/send', 'TalkController@send');
	Route::get('conversation/delete/{id}', 'TalkController@deleteConversation');



	Route::get('auth/login', 'LoginController@login');
	Route::post('auth/login', 'LoginController@makeLogin');
	Route::get('auth/logout', 'LoginController@logout');
});
