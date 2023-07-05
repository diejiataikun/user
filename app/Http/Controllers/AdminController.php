<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * @param $request
     * @return array
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
}
