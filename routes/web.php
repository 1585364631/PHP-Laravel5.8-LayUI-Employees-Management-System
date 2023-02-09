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


Route::get('login', 'Home\UserController@login')->name('userLogin');
Route::post('check', 'Home\UserController@check')->name('userCheck');
Route::get('logout', 'Home\UserController@logout')->name('userLogout');

Route::middleware(['user'])->group(function () {
    Route::get('/', 'Home\IndexController@index')->name('userIndex');
    Route::get('post', 'Home\IndexController@post')->name('postTest');
    Route::get('test', 'Home\IndexController@test')->name('testTest');
    Route::prefix('Department')->group(function () {
        Route::get('/', 'Home\DepartmentController@index')->name('department');
        Route::any('add', 'Home\DepartmentController@add')->name('departmentAdd');
        Route::any('update', 'Home\DepartmentController@update')->name('departmentUpdate');
        Route::any('delete', 'Home\DepartmentController@delete')->name('departmentDelete');
        Route::any('get', 'Home\DepartmentController@get')->name('departmentGet');
    });
    Route::prefix('Employee')->group(function () {
        Route::get('/', 'Home\EmployeeController@index')->name('employee');
        Route::any('/add', 'Home\EmployeeController@add')->name('employeeAdd');
        Route::any('/update', 'Home\EmployeeController@update')->name('employeeUpdate');
        Route::any('/delete', 'Home\EmployeeController@delete')->name('employeeDelete');
        Route::any('/get', 'Home\EmployeeController@get')->name('employeeGet');
        Route::any('/getFields', 'Home\EmployeeController@getFields')->name('employeeGetFields');
    });
});
