<?php

namespace App\Models;

use http\Message;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Filesystem\Cache;
use Illuminate\Support\Facades\Mail;

class TestModel extends Model
{
     use HasFactory;
     use SoftDeletes;
    /**   * 需要被转换成日期的属性
     *  @var array   */
    protected $dates = ['deleted_at'];
    protected $table = "it_user_information";
    // 指定开启时间戳
    public $timestamps = true;
    // 指定主键
    protected $primaryKey = "id";
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


}
