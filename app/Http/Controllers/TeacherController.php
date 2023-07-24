<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
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
     * 登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        $credentials['account'] = $request['account'];
        $credentials['password'] = $request['password'];
        $res = Teacher::checkteacher($credentials['account']);
        if(is_error($res) == false)
        {
            $token = auth('api')->attempt($credentials);
            return $token ?
                json_success('登录成功!',$token,  200):
                json_fail('登录失败!账号或密码错误',null, 100 ) ;
        } else{
          return json_fail('账户不存在',null,101);
        }
    }
    /**
     * 刷新token
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token = auth('api')->refresh();
        return  json_success('token刷新成功!',$token, 200);
    }

        /**
     * 退出登录
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();
        return  json_success('用户退出登录成功!',null,  200);
    }
}
