<?php

namespace App\Http\Controllers;

use App\Models\Test2Model;
use App\Models\TestModel;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class UserController extends Controller
{
    public function use_mail(Request $request)
    {
        $project=TestModel::send_email($request);
        return $project ?
            json_success('成功',$project,200):
            json_fail('失败',null,100);
    }

    public function export_excel(Request $request)
    {
         $list = TestModel::select_excel($request);
         return (new FastExcel($list))->download('信息表' . '.xlsx');
    }

    public function update_password(Request $request)
    {
        $project = TestModel::update_pass($request);
        return $project ?
            json_success('操作成功!',$project,200):
            json_fail('操作失败!',null,100);
    }

    public function delete(Request $request)
    {
        $project = TestModel::delete_information($request);
        return $project ?
            json_success('操作成功!',$project,200):
            json_fail('操作失败!',null,100);
    }

    public function add_information(Request $request)
    {
        $project = TestModel::add($request);
        return $project ?
            json_success('操作成功!',$project,200):
            json_fail('操作失败!',null,100);
    }
//分批传送数据
    public function in_batches()
    {
        $project = TestModel::batches();
        return $project ?
            json_success('操作成功!',$project,200):
            json_fail('操作失败!',null,100);
    }
    //分页
    public function paging()
    {
        $pageSize = 3;//分页每页的数据量
        $users = TestModel::paginate($pageSize);//使用paginate来进行分页
        return response()->json($users);//返回数据给前端
    }

    //热搜
    public function hot_search(Request $request)
    {
        $project = Test2Model::search($request);//看是否有这个热搜词
        if($project)
        {
            $hotSearches = Test2Model::orderBy('count','desc')->take(10)->get();//查看热搜词，take里面的就是多少热搜次的展现
            return json_success('操作成功!',$hotSearches,200);
        }
        else return json_fail('操作失败!',null,100);
    }
}
