<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\testcontroller;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('11',[testcontroller::class,'text']);
Route::post('use_mail',[UserController::class,'use_mail']);
Route::get('export_excel',[UserController::class,'export_excel']);
Route::post('update_password',[UserController::class,'update_password']);
Route::post('delete',[UserController::class,'delete']);
Route::post('add_information',[UserController::class,'add_information']);
Route::get('in_batches',[UserController::class,'in_batches']);//分批传数据
Route::get('paging',[UserController::class,'paging']);//分页
Route::post('hot_search',[UserController::class,'hot_search']);//热搜
//管理员注册登录
Route::prefix('admin')->group(function () {
    Route::post('register',[AdminController::class,'register']);  //注册
    Route::post('login',[AdminController::class,'login']);   //登录
});
Route::group([ 'middleware'=>'jwt.role:admins'], function () {
    Route::post('admin/logout',[AdminController::class,'logout']);  //登出
    Route::post('admin/refresh',[AdminController::class,'refresh']);  //刷新token
    Route::get('admin/test',[AdminController::class,'test']);
});
//Route::middleware('jwt.role:admin','jwt.auth')->prefix('admin')->group(function () {
//    Route::post('logout',[AdminController::class,'logout']);  //登出
//    Route::post('refresh',[AdminController::class,'refresh']);  //刷新token
//    Route::get('test',[AdminController::class,'test']);
//});

