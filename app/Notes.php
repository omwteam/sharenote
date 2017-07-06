<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'notes';
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = true;
    protected $fillable = ['title','content','origin_content','u_id','f_id','isPrivate','type','active'];
//    protected $guarded = ['created_at','updated_at'];
}
