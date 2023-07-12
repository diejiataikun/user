<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**   * 需要被转换成日期的属性
     *  @var array   */
    protected $dates = ['deleted_at'];
    protected $table = "topic";
    // 指定开启时间戳
    public $timestamps = true;
    // 指定主键
    protected $primaryKey = "to_id";
    // 指定不允许自动填充的字段，字段修改的黑名单
    protected $guarded = [];

    /**
     * 添加客观题
     * @param $request
     * @return false|string
     */
    public static function insert_titile($request)
    {
        try {
            if($request['option_c']==NULL)
                $option_c=NULL;
            if($request['option_d']==NULL)
                $option_d=NULL;
            $result = self::create([
                'knowledge_point'=>$request['knowledge_point'],
                'question_type'=>$request['question_type'],
                'question_text'=>$request['question_text'],
                'option_a'=>$request['option_a'],
                'option_b'=>$request['option_b'],
                'option_c'=>$request['option_c'],
                'option_d'=>$request['option_d'],
                'correct_answer'=>$request['correct_answer'],
                'explanation'=>$request['explanation'],
            ])->to_id;
            return $result ?
                $result:
                false;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }

    /**
     * 修改客观题
     * @param $id
     * @param $request
     * @return string
     */
    public static function update_title_one($id,$request)
    {
        try {
            if($request['option_c']==null){
                Topic::where('to_id',$id)->update([
                    'option_c'=>null,
                ]);
            }else{
                Topic::where('to_id',$id)->update([
                    'option_c'=>$request['option_c'],
                ]);
            }
            if($request['option_d']==null){
                Topic::where('to_id',$id)->update([
                    'option_d'=>null,
                ]);
            }else{
                Topic::where('to_id',$id)->update([
                    'option_c'=>$request['option_d'],
                ]);
            }
            $result = Topic::where('to_id',$id)->update([
                'knowledge_point'=>$request['knowledge_point'],
                'question_type'=>$request['question_type'],
                'question_text'=>$request['question_text'],
                'option_a'=>$request['option_a'],
                'option_b'=>$request['option_b'],
                'correct_answer'=>$request['correct_answer'],
            ]);
            return $result;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }

    public static function delete_title_one($id,$request)
    {
        try {
            $result = Topic::where('to_id',$id)->delete();
            return $result;
        }catch (Exception $e) {
            return 'error'.$e->getMessage();
        }
    }

}
