<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
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

}
