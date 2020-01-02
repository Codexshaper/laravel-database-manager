<?php

Route::group(['prefix' => config('dbm.prefix'), 'namespace' => config('dbm.controller_namespace')], function () {

    /* Helpers Route*/
    Route::get('assets', 'ManagerController@assets')->name('dbm.asset');

    // Database
    Route::group(['prefix' => 'database', 'middleware' => 'auth:api'], function () {

        // Route::get('/', 'DatabaseController@index')->name('dbm.table');
        Route::get('/tables', 'DatabaseController@all');
        Route::get('/table/{name}', 'DatabaseController@getTable');
        // Route::get('/table/create', 'DatabaseController@showTableForm');
        Route::post('/table', 'DatabaseController@create');
        Route::put('/table', 'DatabaseController@update');
        Route::delete('/table', 'DatabaseController@delete');
        // Backup
        // Route::get('/backups', 'BackupController@index')->name('dbm.backup');
        Route::get('/getBackups', 'BackupController@backups');
        Route::post('/backup', 'BackupController@backup');
        Route::put('/backup', 'BackupController@restore');
        Route::delete('/backup', 'BackupController@delete');
        Route::get('/download', 'BackupController@download');
        // Permissions
        // Route::get('/permissions', 'PermissionController@index')->name('dbm.permission');
        Route::get('/permissions', 'PermissionController@all');
        Route::post('/permissions/assignUserPermissions', 'PermissionController@assignUserPermissions');
        Route::put('/permissions/syncUserPermissions', 'PermissionController@syncUserPermissions');
        Route::delete('/permissions/deleteUserPermissions', 'PermissionController@deleteUserPermissions');
        // Template
        Route::post('/template', 'DatabaseController@saveTemplate');
        Route::delete('/template', 'DatabaseController@removeTemplate');

        // Unique ID
        Route::get('getUniqueId', 'DatabaseController@getUniqueId');

    });

    /*
     * C = Create
     * R = Read
     * U = Update/Edit
     * D = Delete
     */
    Route::group(['prefix' => 'crud', 'middleware' => 'auth:api'], function () {

        // Route::get('/', 'CrudController@index')->name('dbm.crud');
        Route::get('/tables', 'CrudController@all');
        // Add or Edit Cruds
        // Route::get('/{table}/add-edit', 'CrudController@addEdit')->name('crudAddEdit');
        Route::get('/table/details/all', 'CrudController@getObjectDetails');
        Route::post('/{table}', 'CrudController@storeOrUpdate');
        Route::delete('/{table}', 'CrudController@delete');
        // Relationship
        Route::post('/relationship/add', 'CrudController@addRelation');
        Route::put('/relationship/update', 'CrudController@updateRelation');
        Route::delete('/relationship/delete', 'CrudController@deleteRelation');
        Route::get('/relationship/get', 'CrudController@getRelation');

    });

    Route::group(['prefix' => 'table', 'middleware' => 'auth:api'], function () {

        // Builder
        // Route::get('/builder/{name}', 'DatabaseController@showTableBuilder');
        // Records
        // Route::get('/records/{object}', 'CrudController@getTableRecords')->name('tableRecords');
        Route::get('/getTable', 'CrudController@getTableDetails')->name('getTableDetails');
        Route::get('/getColumns', 'CrudController@getTableColumns')->name('getTableColumns');
        Route::post('/record', 'CrudController@addRecord')->name('addRecord');
        Route::put('/record', 'CrudController@updateRecord')->name('updateRecord');
        Route::delete('/record', 'CrudController@deleteRecord')->name('deleteRecord');

    });

    Route::post('login', 'UserController@login');
    Route::post('oauth/token', 'UserController@getPersonalAccessToken');
    Route::delete('logout', 'UserController@logout')->middleware('auth:api');

    // Test
    // Route::get('/create', 'DatabaseController@store');
    // Route::get('/update', 'DatabaseController@updateSchema');
});
