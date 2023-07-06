<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admins extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "Admin";
    // 指定开启时间戳
    public $timestamps = true;
    // 指定主键
    protected $primaryKey = "id";
    // 指定不允许自动填充的字段，字段修改的黑名单
    protected $guarded = [];

    // 实现接口中定义的方法
    public function getAuthIdentifierName()
    {
        return 'id'; // 返回模型的主键字段名
    }

    public function getAuthIdentifier()
    {
        return $this->id; // 返回模型的主键值
    }

    public function getAuthPassword()
    {
        return $this->password; // 返回模型的密码字段值
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
        return ["role"=>"admins"];
    }



    /**
     * @param $account
     * @return string
     */
    public static function checknumber($account)
    {
        try{
            $count = Admins::select('account')
                ->where('account',$account)
                ->count();
            return $count;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }

    /**
     * @param $registeredInfo
     * @return string
     */
    public static function createUser($registeredInfo)
    {
        try {
            $student_id = Admins::create([
                'account' => $registeredInfo['account'],
                'password' => $registeredInfo['password'],
            ])->id;
            return $student_id;
        } catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }


 }
