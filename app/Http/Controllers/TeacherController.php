<?php

namespace App\Http\Controllers;

use App\Models\Fraction;
use App\Models\Student;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
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
            $token = auth('teacher')->attempt($credentials);
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
        $token = auth('teacher')->refresh();
        return  json_success('token刷新成功!',$token, 200);
    }

        /**
     * 退出登录
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('teacher')->logout();
        return  json_success('用户退出登录成功!',null,  200);
    }

    /**
     * 初始化学生信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function student_information(Request $request)
    {
        $account = $request['account'];
        $project = Student::get_information($account);
        return $project ?
            json_success('该实验室学生信息',$project,200):
            json_fail('操作失败!',null,100);

    }

    /**
     * 学生思考题作答
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch(Request $request)
    {
        $id = $request['u_id'];
        $result = Student::select('think_questions')
            ->join('fraction','student.fraction_id','=','fraction.f_id')
            ->where('u_id',$id)
            ->get();
        return $result ?
            json_success('学生思考题作答',$result,200):
            json_fail('返回失败',null,100);
    }

    /**
     * 批改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function correcting(Request $request)
    {
        $subjective_questions_score = $request['subjective_questions_score'];
        $id = $request['u_id'];
        $add = Student::add_questions_score($request,$subjective_questions_score,$id);
        if($add)
        {
            $result = Student::add_score($request,$id);
            return $result ?
                json_success('批改成功',$result,200):
                json_fail('批改失败',null,100);
        }else return json_fail('批改失败，检查是否数据错误',null,101);
    }


    public function exportPdf(Request $request)
    {
        // 根据 laboratory_id 查找学生的 id
        $students = Student::where('laboratory_id', $request['laboratory_id'])->get();

        if ($students->isEmpty()) {
            // 处理找不到学生的情况
            return json_fail('找不到学生',null,100);
        }

        // 创建一个数组来存储所有学生的数据
        $data = [];

        foreach ($students as $student) {
            // 根据学生的 id 查找相关的 fraction_id
            $fraction_id = $student->fraction_id;

            // 使用 fraction_id 查找相关的分数数据
            $fraction = Fraction::find($fraction_id);

            if ($fraction) {
                // 将学生和分数数据添加到数组中
                $data[] = [
                    'account' => $student->account,
                    'name' => $student->name,
                    'grade' => $student->grade,
                    'specialized' => $student->specialized,
                    'score' => $fraction->score,
                ];
            }
        }

        // 使用 PDF 库生成 PDF
        $pdf = PDF::loadView('pdf_template', compact('data'));

        // 返回生成的 PDF
        return $pdf->download('exported-data.pdf');
    }


}
