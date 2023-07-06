<?php

namespace App\Http\Controllers;

use App\Models\Admins;
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request){
        $registeredInfo = self::userHandle($request);
        $count = Admins::checknumber($registeredInfo['account']);   //检测账号密码是否存在
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

}
