<?php

Route::group([
    'prefix'     => trim(config('dbm.prefix')),
    'middleware' => 'auth:api',
    'namespace'  => config('dbm.controller_namespace'),
], function () {

    // Helpers Route
    Route::get('assets', 'ManagerController@assets')->name('dbm.asset');
    // Database
    Route::get('/tables', 'TableController@all');
    Route::get('/table/{name}', 'TableController@getTable');
    Route::post('/table', 'DatabaseController@create');
    Route::put('/table', 'DatabaseController@update');
    Route::delete('/table', 'DatabaseController@delete');
    // Backup
    Route::get('/getBackups', 'BackupController@backups');
    Route::post('/backup', 'BackupController@backup');
    Route::put('/backup', 'BackupController@restore');
    Route::delete('/backup', 'BackupController@delete');
    Route::get('/download', 'BackupController@download');
    // Permissions
    Route::get('/permissions', 'PermissionController@all');
    Route::post('/permissions/assignUserPermissions', 'PermissionController@assignUserPermissions');
    Route::put('/permissions/syncUserPermissions', 'PermissionController@syncUserPermissions');
    Route::delete('/permissions/deleteUserPermissions', 'PermissionController@deleteUserPermissions');
    // Template
    Route::post('/template', 'TemplateController@save');
    Route::delete('/template', 'TemplateController@remove');
    /*
     * C = Create
     * R = Read
     * U = Update/Edit
     * D = Delete
     */
    Route::get('/crud/tables', 'ObjectController@all');
    Route::get('/crud/details/{table}', 'ObjectController@getObjectDetails');
    Route::post('/crud', 'CrudController@storeOrUpdate');
    Route::delete('/crud/{table}', 'CrudController@delete');
    // Relationship
    Route::get('/relationship', 'RelationController@get');
    Route::post('/relationship', 'RelationController@add');
    Route::put('/relationship', 'RelationController@update');
    Route::delete('/relationship', 'RelationController@delete');
    // Table
    Route::group(['prefix' => 'table'], function () {
        Route::get('/details/{table}', 'RecordController@getTableDetails');
        Route::get('/columns/{table}', 'TableController@getTableColumns');
    });
    // Record
    Route::post('/record', 'RecordController@store');
    Route::put('/record', 'RecordController@update');
    Route::delete('/record', 'RecordController@delete');
});

Route::group([
    'prefix'    => trim(config('dbm.prefix')),
    'namespace' => config('dbm.controller_namespace'),
], function () {
    // User
    Route::post('/login', 'UserController@login');

    Route::get('menus', 'MenuController@get');
});
