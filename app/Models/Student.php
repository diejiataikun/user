<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Student extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use SoftDeletes;

    /**   * 需要被转换成日期的属性
     *  @var array   */
    protected $dates = ['deleted_at'];
    protected $table = "student";
    // 指定开启时间戳
    public $timestamps = true;
    // 指定主键
    protected $primaryKey = "u_id";
    // 指定不允许自动填充的字段，字段修改的黑名单
    protected $guarded = [];

    /**
     * 初始化学生信息
     * @param $account
     * @return string
     */
    public static function get_information($account)
    {
        try {
             $lab = Teacher::where('account',$account)
                 ->join('laboratory','teacher.lab_code_id','=','laboratory.lab_code')
                 ->get();
//             $fraction = Teacher::where('account',$account)
//             ->join('fraction','');
             $infor = Student::select('u_id','account','name','grade','specialized','lab_code','lab_name','score')
                 ->join('laboratory','student.laboratory_id','=','laboratory.lab_code')
                 ->join('fraction','student.fraction_id','=','fraction.f_id')
                 ->where('laboratory_id',$lab[0]->lab_code)
                 ->get();
             return $infor;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }

    /**
     * 检测用户是否存在
     * @param $account
     * @return string
     */
    public static function checknumber($account)
    {
        try{
            $count = Student::select('account')
                ->where('account',$account)
                ->count();
            return $count;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }


    /**
     * 创建账户
     * @param $registeredInfo
     * @param $request
     * @return false|string
     */
    public static function createUser($registeredInfo,$request)
    {
        try {
            $porject = Laboratory::where('lab_name',$request['lab_name'])->get();
            $student_id = Student::create([
                'account' => $registeredInfo['account'],
                'password' => $registeredInfo['password'],
                'email' => $request['email'],
                'name' => $request['name'],
                'grade' => $request['grade'],
                'specialized' => $request['specialized'],
                'laboratory_id'=>$porject[0]->lab_code,
            ])->u_id;
            return $student_id ?
                $student_id:
                false;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }

    /**
     * 发送邮箱
     * @param $request
     * @return array|string
     */
    public static function judegment_email($request)
    {
        try {
            // 生成一个随机密钥
            $key = random_bytes(32);// 256位密钥
            // 待加密的数据
            $random=rand(100000,999999);
            // 生成一个随机的IV（Initialization Vector）
            $iv = random_bytes(16); // 128位IV
            // 使用 AES 加密算法和生成的密钥、IV进行加密
            $encryptedData = openssl_encrypt($random, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
            $email=$request->input("email");
            Mail::raw("您的验证码是:".$random, function($message) use ($email) {
                $message->to($email)->subject('验证码');
            });
            $response = [
                'encryptedData' => base64_encode($encryptedData),
                'key' => base64_encode($key),
                'iv' => base64_encode($iv)
            ];
            return $response;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }

    /**
     * 添加成绩
     * @param $request
     * @param $subjective_questions_score
     * @param $id
     * @return string
     */
    public static function add_questions_score($request,$subjective_questions_score,$id)
    {
        try{
            $f_id = Student::where('u_id',$id)->get();
            $result = Fraction::where('f_id',$f_id[0]->fraction_id)->first();
            $result->subjective_questions_score =$subjective_questions_score;
            $result->save();
            return $result->save();
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }

    public static function add_score($request,$id)
    {
        try{
            $fraction_id = Student::where('u_id',$id)->get();
            $f_id = Fraction::where('f_id',$fraction_id[0]->fraction_id)->get();
            $score = $f_id[0]->objective_questions_score + $f_id[0]->subjective_questions_score;
            $result = Fraction::where('f_id',$fraction_id[0]->fraction_id)->first();
            $result->score = $score;
            $result->save();
            return $result->save();
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }



    /**
     * 获取会储存到 jwt 声明中的标识
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * 返回包含要添加到 jwt 声明中的自定义键值对数组
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ["role"=>"student"];
    }



}
