<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feeds extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'feedback';
    /**
     * 写入白名单
     * @var array
     */
    protected $fillable = ['title','content','type','active','contact','ip','user_id'];

    /**
     * 写入黑名单
     * @var array
     */
//    protected $guarded = ['created_at','updated_at','ip','user_id'];

    /**
     *  是否公开
     * @param $query
     * @param string $type 默认是公开
     * @return mixed
     */
    public function scopeIsPrivate($query, $type = '1')
    {
        return $query->where('isPrivate', $type);
    }

    /**
     * 动态模糊匹配
     * @param $query
     * @param $field
     * @param $value
     * @return mixed
     */
    public function scopeLike($query, $field,$value)
    {
        return $query->where($field,'like', '%'.$value.'%');
    }
//
//    /**
//     * @param $value
//     */
//    public function setActiveAttribute($value)
//    {
//        $active = ["未处理","已知悉未处理","处理中","处理完毕","废弃"];
//        $this->attributes['active'] = $active[$value];
//    }


    public function getActiveAttribute($value)
    {
        $active = ["未处理","已知悉未处理","处理中","处理完毕","废弃"];
        return $this->attributes['active'] = $active[$value];
    }
}
