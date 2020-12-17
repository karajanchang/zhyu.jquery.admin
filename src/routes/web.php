<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 05:28
 */

Route::get('/ajax/{model}-{act}/{resource?}', 'ZhyuJqueryAdmin\Controller\AjaxController@index')->name('ajax');

Route::get('/lang/{locale}', function ($locale) {
    session()->put('locale', $locale);
    return Redirect::back();
})->middleware('web');

Route::get('/logout', function() {
    auth()->logout();
    return Redirect::to('/');
})->middleware('web')->name('logout');


Route::group(['prefix' => '/admin', 'middleware' => ['web', 'auth'], 'as' =>  'admin.'  ], function () {
    Route::resource('/resources', 'ZhyuJqueryAdmin\Controller\ResourceController');
    Route::get('/usergroups/{id}/priv', 'ZhyuJqueryAdmin\Controller\UsergroupController@priv')->name('usergroups.priv');
    Route::post('/usergroups/{id}/priv', 'ZhyuJqueryAdmin\Controller\UsergroupController@privSave')->name('usergroups.priv');
    Route::get('/users/{id}/priv', 'ZhyuJqueryAdmin\Controller\UserController@priv')->name('users.priv');
    Route::post('/users/{id}/priv', 'ZhyuJqueryAdmin\Controller\UserController@privSave')->name('users.priv');
    Route::resource('/usergroups', 'ZhyuJqueryAdmin\Controller\UsergroupController');
    Route::resource('/users', 'ZhyuJqueryAdmin\Controller\UserController');
});

