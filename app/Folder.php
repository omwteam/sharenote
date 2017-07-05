<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'folders';
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = true;
//    protected $fillable = ['title','u_id','p_id','active'];
    protected $guarded = ['created_at','updated_at'];
}
