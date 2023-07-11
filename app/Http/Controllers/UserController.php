<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class UserController extends Controller
{
    public function use_mail(Request $request)
    {
        $project=Laboratory::send_email($request);
        return $project ?
            json_success('成功',$project,200):
            json_fail('失败',null,100);
    }

    public function export_excel(Request $request)
    {
         $list = Laboratory::select_excel($request);
         return (new FastExcel($list))->download('信息表' . '.xlsx');
    }

    public function update_password(Request $request)
    {
        $project = Laboratory::update_pass($request);
        return $project ?
            json_success('操作成功!',$project,200):
            json_fail('操作失败!',null,100);
    }

    public function delete(Request $request)
    {
        $project = Laboratory::delete_information($request);
        return $project ?
            json_success('操作成功!',$project,200):
            json_fail('操作失败!',null,100);
    }

    public function add_information(Request $request)
    {
        $project = Laboratory::add($request);
        return $project ?
            json_success('操作成功!',$project,200):
            json_fail('操作失败!',null,100);
    }
//分批传送数据
    public function in_batches()
    {
        $project = Laboratory::batches();
        return $project ?
            json_success('操作成功!',$project,200):
            json_fail('操作失败!',null,100);
    }


    //热搜
    public function hot_search(Request $request)
    {
        $project = Teacher::search($request);//看是否有这个热搜词
        if($project)
        {
            $hotSearches = Teacher::orderBy('count','desc')->take(10)->get();//查看热搜词，take里面的就是多少热搜次的展现
            return json_success('操作成功!',$hotSearches,200);
        }
        else return json_fail('操作失败!',null,100);
    }




}
