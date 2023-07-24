<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use App\Models\Laboratory;
use App\Models\Subjective;
use App\Models\Teacher;
use App\Models\Topic;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * @param $request
     * @return array
     *加密数据（值得注意的是，本方法因为无需密码登录，故而获取的psw和account一致）
     */
    protected function userHandle($request)   //对密码进行哈希256加密
    {
        $registeredInfo['account'] = $request['account'];
        $registeredInfo['password'] = $request['password'];
        $registeredInfo['password'] = bcrypt($registeredInfo['password']);
        return $registeredInfo;
    }

    /**
     * 添加管理员账号
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request){
        $registeredInfo = self::userHandle($request);
        $count = Teacher::checknumber($registeredInfo['account']);   //检测账号密码是否存在
        if (is_error($count) == true){
            return json_fail('注册失败!检测是否存在的时候出错啦',$count,100  ) ;
        }
        if ($count == 0){
            $student_id = Admins::createUser($registeredInfo);
            if (is_error($student_id) == true){
                return json_fail('注册失败!添加数据的时候有问题',$student_id,100  ) ;
            }
            return json_success('注册成功!',$student_id,200  ) ;
        }
        return json_fail('注册失败!该用户信息已经被注册过了',null,101 ) ;
    }

    /**
     * 登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
            $credentials['account'] = $request['account'];
            $credentials['password'] = $request['password'];
            $token = auth('admin')->attempt($credentials);
            return $token ?
                json_success('登录成功!',$token,  200):
                json_fail('登录失败!账号或密码错误',null, 100 ) ;
    }

    /**
     * 退出登录
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('admin')->logout();

        return  json_success('用户退出登录成功!',null,  200);
    }

    /**
     * 刷新token
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token = auth('admin')->refresh();
        return  json_success('token刷新成功!',$token, 200);
    }
    public function test()
    {
        $project = true;
        return $project ?
            json_success('操作成功!',$project,200):
            json_fail('操作失败!',null,100);
    }


    /**
     * 添加实验室
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add_laboratory(Request $request)
    {
        $count = Laboratory::checklaboratory($request['lab_code']);
        if (is_error($count) == true){
            return json_fail('添加失败!检测是否存在的时候出错啦',$count,100  ) ;
        }
        if ($count == 0){
            $laboratory_id = Laboratory::createlaboratory($request);
            if (is_error($laboratory_id) == true){
                return json_fail('注册失败!添加数据的时候有问题',$laboratory_id,100  ) ;
            }
            return json_success('注册成功!',$laboratory_id,200  ) ;
        }
        return json_fail('注册失败!该用户信息已经被注册过了',null,101 ) ;
    }

    /**
     * 添加教师账号
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add_teacher_account(Request $request)
    {
        $count = Teacher::checkteacher($request['account']);
        if (is_error($count) == true){
            return json_fail('添加失败!检测是否存在的时候出错啦',$count,100  ) ;
        }
        $result = Laboratory::checklaboratory_teacher($request['lab_name']);
        if ($result == 0){
            return json_fail('添加失败!检测是否存在的时候出错啦',$count,100  ) ;
        }
        //如果没有这个账号并且有这个实验室
        if ($count == 0 && $result ){
            $teacher_id = Teacher::createteacher($request);
            if (is_error($teacher_id) == true){
                return json_fail('注册失败!添加数据的时候有问题',$teacher_id,100  ) ;
            }
            return json_success('注册成功!',$teacher_id,200  ) ;
        }
        return json_fail('注册失败!该用户信息已经被注册过了',null,101 ) ;
    }

    /**
     * 查看实验室所有数据
     * @return \Illuminate\Http\JsonResponse
     */
    public function view_the_lab()
    {
        $result = Laboratory::view_all_lab();
        return json_success('所有实验室数据',$result,200);
    }

    /**
     * 删除实验室数据(暂不需要用)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
  public function delete_the_lab(Request $request)
  {
      $result = Laboratory::delete_lab($request['lab_code']);
      return json_success('删除成功',$result,200);
  }

    /**
     * 查看老师账号信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
  public function view_the_teacher(Request $request)
  {
      $result = Teacher::view_all_teacher($request);
      return json_success('教师账号的所有数据',$result,200);
  }

    /**
     * 修改教师账户信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
  public function update_teacher(Request $request)
  {
      $result = Teacher::update_account($request);
      if(is_error($result) == true){
          return json_fail('修改数据失败!',$result,100  ) ;
      }else{
          return json_success('修改数据成功!',$result,200 );
      }
  }

    /**
     * 删除教师账户
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
  public function delete_account_teacher(Request $request)
  {
      $id = $request['t_id'];
      $result = Teacher::delete_account($id);
      if(is_error($result) == true){
          return json_fail('删除数据失败!',$result,100  ) ;
      }else{
          return json_success('删除数据成功!',$result,200 );
      }
  }

    /**
     * 分页数据
     * @return \Illuminate\Http\JsonResponse
     */
    public function paging()
    {
        $pageSize = 20;//分页每页的数据量
        $users = Topic::paginate($pageSize);//使用paginate来进行分页
        $users2 = Subjective::select('su_id','subjective_questions','subjective_answer')->get();
        $res = [
            $users,$users2
        ];
        return json_success('分页的题库数据，一页20题,最后会有主观题',$res,200);
    }

    /**
     * 插入题目
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert_a_title(Request $request)
    {
        $project = Topic::insert_titile($request);
        return $project ?
            json_success('操作成功!',$project,200):
            json_fail('操作失败!',null,100);
    }

    /**
     * 插入主观题目
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add_subjective(Request $request)
    {
        $project = Subjective::add_sub($request);
        return $project ?
            json_success('添加成功!',$project,200):
            json_fail('添加失败!',null,100);
    }

    /**
     * 修改客观题
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_title(Request $request)
    {
        $id = $request['to_id'];
        $res = Topic::update_title_one($id,$request);
        if (is_error($res) == true){
            return json_fail('修改题目失败!',$res,100  ) ;
        }else{
            return json_success('修改题目成功!',$res,200 );
        }
    }

    /**
     * 修改主观题
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_subjective(Request $request)
    {
        $id = $request['su_id'];
        $res = Subjective::update_subjective_one($id,$request);
        if (is_error($res) == true){
            return json_fail('修改题目失败!',$res,100  ) ;
        }else{
            return json_success('修改题目成功!',$res,200 );
        }
    }

    /**
     * 删除选择题
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete_title(Request $request)
    {
        $id = $request['to_id'];
        $res = Topic::delete_title_one($id,$request);
        if (is_error($res) == true){
            return json_fail('删除题目失败!',$res,100  ) ;
        }else{
            return json_success('删除题目成功!',$res,200 );
        }
    }

    /**
     * 删除思考题
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete_subjective(Request $request)
    {
        $id = $request['su_id'];
        $res = Subjective::delete_subjective_one($id,$request);
        if (is_error($res) == true){
            return json_fail('删除题目失败!',$res,100  ) ;
        }else{
            return json_success('删除题目成功!',$res,200 );
        }
    }
}
