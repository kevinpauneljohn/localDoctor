<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/','LandingPageController');

/*------------------Custom auth------------------------*/
Route::get('/login','CustomAuth\LoginController@login')->name('login');
Route::post('/login','CustomAuth\LoginController@authenticate')->name('login.authenticate');
/*------------------End Of Custom auth------------------------*/

Route::group(['middleware' => ['auth']], function (){
    Route::get('/dashboard','DashboardController@dashboard')->name('dashboard');
    Route::post('/logout','CustomAuth\LoginController@logout')->name('logout');
});

Route::group(['middleware' => ['auth']], function(){
    Route::resources([
        'clinics' => 'ClinicController',
    ]);

});


/*client*/
Route::get('/clients','ClientController@index')->name('clients.index')->middleware(['auth','permission:view client']);
Route::post('/clients','ClientController@store')->name('clients.store')->middleware(['auth','permission:add client']);
Route::put('/clients/{client}','ClientController@update')->name('clients.update')->middleware(['auth','permission:edit client']);
Route::get('/clients/{client}','ClientController@show')->name('clients.show')->middleware(['auth']);
Route::get('/clients-list','ClientController@clientList')->name('clients.list')->middleware(['auth','permission:view client']);

Route::post('/client-form','ClientController@editForm')->name('client.form')->middleware(['auth','permission:edit client']);
/*end client*/

/*permissions*/
Route::get('/permissions','PermissionController@index')->name('permissions.index')->middleware(['auth']);
Route::post('/permissions','PermissionController@store')->name('permissions.store')->middleware(['auth','permission:add permission']);
Route::put('/permissions/{permission}','PermissionController@update')->name('permissions.update')->middleware(['auth','permission:edit permission']);
Route::get('/permissions/{permission}','PermissionController@show')->name('permissions.show')->middleware(['auth','permission:assign role to permission']);

/*roles*/
Route::get('/roles','RolesController@index')->name('roles.index')->middleware(['auth','permission:view role']);
Route::post('/roles','RolesController@store')->name('roles.store')->middleware(['auth','permission:add role']);
Route::put('/roles/{role}','RolesController@update')->name('roles.update')->middleware(['auth','permission:edit role']);
Route::delete('/roles/{role}','RolesController@destroy')->name('roles.destroy')->middleware(['auth','permission:delete role']);

/*address*/
Route::get('/address/region/{regCode}','AddressController@getRegion')->name('region')->middleware(['auth']);
Route::get('/address/state/{regCode}','AddressController@getState')->name('state')->middleware(['auth']);
Route::get('/address/city/{provCode}','AddressController@getCity')->name('city')->middleware(['auth']);

Route::group(['middleware' => ['auth','permission:assign role to permission']],function (){
    Route::post('/permission-get-roles','PermissionController@permissionAssignedRoles')->name('permission.roles');
    Route::post('/permission-set-roles','PermissionController@permissionSetRole')->name('permission.set.roles');
});
Route::group(['middleware' => ['auth','permission:view clinic']],function (){
    Route::get('/clinic-list','ClinicController@clinicList')->name('clinics.list');
});
Route::group(['middleware' => ['auth','permission:view role']],function (){
    Route::get('/roles-list','RolesController@rolesList')->name('roles.list');
});
Route::group(['middleware' => ['auth','permission:view permission']],function (){
    Route::get('/permissions-list','PermissionController@permissionList')->name('permissions.list');
});
