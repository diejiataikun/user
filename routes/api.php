<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
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

Route::post('hot_search',[UserController::class,'hot_search']);//热搜
Route::get('use',[UserController::class,'use']);


//管理员注册登录
Route::prefix('admin')->group(function () {
    Route::post('register',[AdminController::class,'register']);  //注册
    Route::post('login',[AdminController::class,'login']);   //登录
});

Route::middleware('jwt.role:admins')->prefix('admin')->group(function (){
    Route::post('logout',[AdminController::class,'logout']);  //登出
    Route::post('refresh',[AdminController::class,'refresh']);  //刷新token
    Route::post('add_laboratory',[AdminController::class,'add_laboratory']);//添加实验室
    Route::get('view_the_lab',[AdminController::class,'view_the_lab']);//查看所有实验室
    Route::post('delete_the_lab',[AdminController::class,'delete_the_lab']);//删除实验室数据(暂时不需要)
    Route::post('add_teacher_account',[AdminController::class,'add_teacher_account']);//添加老师账号
    Route::get('view_the_teacher',[AdminController::class,'view_the_teacher']);//查看老师账号信息
    Route::post('update_teacher',[AdminController::class,'update_teacher']);//修改老师账号信息
    Route::post('delete_account_teacher',[AdminController::class,'delete_account_teacher']);//删除教师账户信息
    Route::get('paging',[AdminController::class,'paging']);//分页题库数据
    Route::post('insert_a_title',[AdminController::class,'insert_a_title']);//插入客观题目
    Route::post('add_subjective',[AdminController::class,'add_subjective']);//插入主观题目
    Route::post('update_title',[AdminController::class,'update_title']);//修改客观题
    Route::post('update_subjective',[AdminController::class,'update_subjective']);//修改主观题
    Route::post('delete_title',[AdminController::class,'delete_title']);//删除选择题
    Route::post('delete_subjective',[AdminController::class,'delete_subjective']);//删除选择题
    Route::get('test',[AdminController::class,'test']);//测试用
});
Route::prefix('teach')->group(function (){
    Route::post('login',[TeacherController::class,'login']);   //登录
});
Route::middleware('jwt.role:teacher')->prefix('teach')->group(function () {
    Route::post('refresh',[TeacherController::class,'refresh']);//刷新token
    Route::post('logout',[TeacherController::class,'logout']);//退出登录
    Route::post('student_information',[TeacherController::class,'student_information']);//学生信息
    Route::post('fetch',[TeacherController::class,'fetch']);//获取思考题
    Route::post('correcting',[TeacherController::class,'correcting']);//批改
});
Route::get('exportPdf',[UserController::class,'exportPdf']);//导出pdf测试

Route::prefix('student')->group(function (){
    Route::post('register',[StudentController::class,'register']);//注册
    Route::post('receive_mailbox',[StudentController::class,'receive_mailbox']);//获取验证码
    Route::post('login',[StudentController::class,'login']);//登录
});


