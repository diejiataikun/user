<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class StudentController extends Controller
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
     * 注册
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $registeredInfo = self::userHandle($request);
        $count = Student::checknumber($registeredInfo['account']);   //检测账号密码是否存在
        if (is_error($count) == true){
            return json_fail('注册失败!检测是否存在的时候出错啦',$count,100  ) ;
        }
        if ($count == 0){
            $student_id = Student::createUser($registeredInfo,$request);
            if (is_error($student_id) == true){
                return json_fail('注册失败!添加数据的时候有问题',$student_id,100  ) ;
            }
            return json_success('注册成功!',$student_id,200  ) ;
        }
        return json_fail('注册失败!该用户信息已经被注册过了',null,101 ) ;
    }

    /**
     * 获取验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function receive_mailbox(Request $request)
    {
        $code = Student::judegment_email($request);//返回验证码
        return $code ?
            json_success('验证码',$code,200):
            json_fail('获取失败',null,100);
    }

    public function login(Request $request)
    {
        $credentials['account'] = $request['account'];
        $credentials['password'] = $request['password'];
        $res = Student::checknumber($credentials['account']);
        if(is_error($res) == false)
        {
            $token = auth('api')->attempt($credentials);
            return $token ?
                json_success('登录成功!',$token,  200):
                json_fail('登录失败!账号或密码错误',null, 100 );
        } else{
            return json_fail('账户不存在',null,101);
        }
    }
}
