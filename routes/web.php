<?php

Route::group(['prefix' => trim(config('dbm.prefix')), 'namespace' => config('dbm.controller_namespace')], function () {
    //Database Table
    Route::get('/', 'DatabaseController@index');
    Route::get('table/builder/{name}', 'DatabaseController@index');
    //Crud
    Route::get('crud', 'CrudController@index');
    Route::get('login', 'CrudController@index');
    Route::get('crud/{table}/add-edit', 'CrudController@index');
    // Record
    Route::get('record/{table}', 'RecordController@index');
    Route::get('record/{table}/add-edit', 'RecordController@index');
    Route::get('record/{table}/add-edit/{id}', 'RecordController@index');
    //Permission
    Route::get('permission', 'PermissionController@index');
    //Backup
    Route::get('backup', 'BackupController@index');
    //Assets
    Route::get('assets', 'ManagerController@assets')->name('dbm.asset');
});
