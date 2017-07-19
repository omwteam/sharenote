<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForgetToken extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'forget_token';

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = ['email','forget_token','expired_time'];
    //
//    public function setTitleAttribute()
//    {
//        $this->attributes['token'] =  bcrypt(str_random(32));
//        $this->attributes['expired_time'] =  time() + 30*60;//默认有效期半个小时
//    }
}
