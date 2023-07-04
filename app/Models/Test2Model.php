<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Test2Model extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**   * 需要被转换成日期的属性
     *  @var array   */
    protected $dates = ['deleted_at'];
    protected $table = "test";
    // 指定开启时间戳
    public $timestamps = true;
    // 指定主键
    protected $primaryKey = "id";
    // 指定不允许自动填充的字段，字段修改的黑名单
    protected $guarded = [];

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
}
