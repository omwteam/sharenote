<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PublicScope implements Scope
{
    /**
     * 应用作用域到给定的Eloquent查询构建器.
     * 查询公开且未标记删除的笔记
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     * @translator laravelacademy.org
     */
    public function apply(Builder $builder, Model $model)
    {
        return $builder->where(['active'=>'1']);
    }
}