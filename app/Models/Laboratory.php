<?php

namespace App\Models;

use Exception;
use http\Message;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Filesystem\Cache;
use Illuminate\Support\Facades\Mail;

class Laboratory extends Model
{
     use HasFactory;
     use SoftDeletes;
    /**   * 需要被转换成日期的属性
     *  @var array   */
    protected $dates = ['deleted_at'];
    protected $table = "laboratory";
    // 指定开启时间戳
    public $timestamps = true;
    // 指定主键
    protected $primaryKey = "l_id";
    // 指定不允许自动填充的字段，字段修改的黑名单
    protected $guarded = [];

    static public function send_email($request)
    {
       $code=rand(100000,999999);
       $email=$request['email'];
       Mail::raw('您的验证码为:'.$code,function ($message)use($email){
           $message->to($email)
           ->subject('验证码');
       });
       return bcrypt($email);
    }

    static public function select_excel($request)
    {
        $project = self::select('id','username','password','age','sex')
            ->get();
        return $project;
    }

    static public function update_pass($requset)
    {
        $result = self::find($requset['id']);
        $result->password=$requset['password'];
        $result->save();
        return $result->id ?
            $result->id:
            false;

    }

    static public function delete_information($request)
    {
        $project = self::find($request['id']);
        if($project)
        {
            $project->delete();
            return true;
        }
        else return false;
    }

    static public function add($request)
    {
        $project = self::create([
            'username'=>$request['username'],
            'password'=>$request['password'],
            'age'=>$request['age'],
            'sex'=>$request['sex'],
        ])->id;
        return $project ?
            $project:
            false;
    }
//分批传给前端
    static public function batches()
    {
        $batchSize = 1;//分批的数量
        $users = [];//创建一个数组
        self::chunk($batchSize,function ($result)use(&$users){//使用chunk方法分批传数据
            foreach ($result as $user){
//                $project = self::select('id','username','password','age','sex')->get();
                $users[]=$user;//将表中数据放入数组中
            }
        });
        return response()->json($users);//返回数据
    }




    /**
     * 检查实验室是否存在
     * @param $lab_code
     * @return string
     */
    public static function checklaboratory($lab_code)
    {
        try{
            $count = Laboratory::select('lab_code')
                ->where('lab_code',$lab_code)
                ->count();
            return $count;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }

    /**
     * 添加教师账号时通过实验室名来检查
     * @param $lab_name
     * @return string
     */
    public static function checklaboratory_teacher($lab_name)
    {
        try{
            $count = Laboratory::select('lab_code')
                ->where('lab_name',$lab_name)
                ->count();
            return $count;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }


    /**
     * 添加实验室
     * @param $request
     * @return false|string
     */
    public static function createlaboratory($request)
    {
        try{
            $laboratory_id=Laboratory::create([
                'lab_code'=>$request['lab_code'],
                'lab_name'=>$request['lab_name'],
            ])->l_id;
            return $laboratory_id ?
                $laboratory_id:
                false;
        }catch (Exception $e) {
        return 'error'.$e->getMessage();
    }
    }

    /**
     * 查看所有实验室数据
     * @return string
     */
    public static function view_all_lab()
    {
        try {
            $laboratory = Laboratory::select('l_id','lab_code','lab_name')
                ->get();
            return $laboratory;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }

    /**
     * 删除实验室数据
     * @param $lab_code
     * @return string
     */
    public static function delete_lab($lab_code)
    {
        try {
            $project = Laboratory::where('lab_code',$lab_code)->delete();
            return $project;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }
}

