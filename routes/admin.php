<?php

group(['prefix' => 'admin', 'middleware' => 'auth' ], function() {
  get('/', 'AdminController@index')->name('admin.home');
  get('/profile', 'AdminController@profile')->name('admin.profile');
});