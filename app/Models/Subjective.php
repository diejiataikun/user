<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subjective extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**   * 需要被转换成日期的属性
     *  @var array   */
    protected $dates = ['deleted_at'];
    protected $table = "subjective";
    // 指定开启时间戳
    public $timestamps = true;
    // 指定主键
    protected $primaryKey = "su_id";
    // 指定不允许自动填充的字段，字段修改的黑名单
    protected $guarded = [];

    /**
     * 插入主观题目
     * @param $request
     * @return false|string
     */
    public static function add_sub($request)
    {
        try {
            $result = self::create([
                'subjective_questions'=>$request['subjective_questions'],
                'subjective_answer'=>$request['subjective_answer'],
            ])->su_id;
            return $result ?
                $result:
                false;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }

    public static function update_subjective_one($id,$request)
    {
        try {
            $result = Subjective::where('su_id',$id)->update([
                'subjective_questions'=>$request['subjective_questions'],
                'subjective_answer'=>$request['subjective_answer'],
            ]);
            return $result;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }

    public static function delete_subjective_one($id,$request)
    {
        try {
            $result = Subjective::where('su_id',$id)->delete();
            return $result;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }

}
