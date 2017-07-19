<?php

namespace App;

use App\Scopes\PublicScope;
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
    /**
     * 添加全局条件
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new PublicScope);
    }
    public function notes()
    {
        return $this->hasMany('App\Notes','f_id','id');
    }
}
