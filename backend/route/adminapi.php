<?php
use think\facade\Route;

Route::group('adminapi',function(){
    // 不分组路由
    Route::group('',function(){
        Route::post('login','adminapi/Login/login');
    });
    // 系统管理
    Route::group('system',function(){
        Route::get('admin/index','adminapi.system/Admin/index');
    });
});