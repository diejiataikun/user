<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
class Teacher extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use SoftDeletes;

    /**   * 需要被转换成日期的属性
     *  @var array   */
    protected $dates = ['deleted_at'];
    protected $table = "teacher";
    // 指定开启时间戳
    public $timestamps = true;
    // 指定主键
    protected $primaryKey = "t_id";
    // 指定不允许自动填充的字段，字段修改的黑名单
    protected $guarded = [];
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
        return ["role"=>"teacher"];
    }

    //热搜
    static public function search($request)
    {
        $keyword = $request['keyword'];
// 检查热搜表中是否存在该关键词的记录
        $hotSearch = self::where('keyword',$keyword)->first();
        if($hotSearch){
            //存在记录，将搜索次数加一
            $hotSearch->increment('count');
        }
        else{
            //不存在记录，则创建新的热搜记录
            self::create([
                'keyword'=>$keyword,
                'count'=>1,
            ]);
        }
        return response()->json(['message'=>'Search successful']);
    }


    /**
     * 检查教师账号是否存在
     * @param $account
     * @return string
     */
    public static function checkteacher($account)
    {
        try {
            $count = Teacher::select('account')
                ->where('account', $account)
                ->count();
            return $count;
        } catch (Exception $e) {
            return 'error' . $e->getMessage();
        }
    }

    /**
     * 添加教师账号
     * @param $request
     * @return false|string
     */
    public static function createteacher($request)
    {
        try{
            $porject = Laboratory::where('lab_name',$request['lab_name'])->get();
            $teacher_id=Teacher::create([
                'account'=>$request['account'],
                'password'=>bcrypt($request['password']),
                'lab_code_id'=>$porject[0]->lab_code,
            ])->t_id;
            return $teacher_id ?
                $teacher_id:
                false;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }

    /**
     * 查看教师所有数据
     * @param $request
     * @return string
     */
    public static function view_all_teacher($request)
    {
        try{
            $result = Teacher::select('t_id','account','password','lab_code_id','lab_name')
                ->join('laboratory','teacher.lab_code_id','=','laboratory.lab_code')
                ->get();
            return $result;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }

    /**
     * 修改教师账户信息
     * @param $request
     * @return string
     */
    public static function update_account($request)
    {
        try{
            $lab_code_id = Laboratory::where('lab_name',$request['lab_name'])->get();
            $project = Teacher::where('t_id',$request['t_id'])->update([
               'account'=>$request['account'],
               'password'=>bcrypt($request['password']),
                'lab_code_id'=>$lab_code_id[0]->lab_code,
            ]);
            return $project;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }

    /**
     * 删除教师账户
     * @param $id
     * @return string
     */
    public static function delete_account($id)
    {
        try {
            $result = Teacher::where('t_id',$id)->delete();
            return $result;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }


}
