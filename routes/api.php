<?php

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

