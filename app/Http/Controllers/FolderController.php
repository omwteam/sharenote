<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Folder;
use Illuminate\Http\Request;

class FolderController extends BaseController
{
    //
    public function store(Request $request)
    {
        // 验证规则
        $rules =  [
            'title' => 'required|max:30',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error('参数验证输错',$validator->errors());
        }
        $params = $request->input();
        if (!isset($params['p_id'])) {
            $params['p_id'] = 0;
        }

        $num = Folder::where(['title'=>$params['title'],'p_id'=>$params['p_id']])->get();
        if (count($num) > 0) {
            return $this->error('文件夹名称重复');
        }
        $params['u_id'] = user()->id;
        $data = Folder::create($params);
        return $this->success('新增成功',$data);
    }

    /**
     * @return array
     */
    public function listAll()
    {
        //
        $categories = Folder::where(array('p_id'=>0,'active'=>'1'))->select('id','title','p_id')->get();
        $allCategories = Folder::where([['p_id','>',0],['active','=','1']])->select('id','title','p_id','u_id')->get();
        return $this->success('请求成功',compact('categories','allCategories'));
    }


    public function update(Request $request)
    {
        // 验证规则
        $rules =  [
            'title' => 'required|max:30',
            'id' => 'required|max:10'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error('参数验证输错',$validator->errors());
        }
        $params = $request->input();
        $pid = isset($params['pid']) ? $params['pid'] : 0;
        $exist = Folder::where(array('p_id'=>$pid,'title'=>$params['title']))->first();
        if (!empty($exist)) {
            return $this->error('文件夹名称重复');
        }
        Folder::where('id',$params['id'])->update(array('title'=>$params['title']));

        return $this->success('修改成功',Folder::find($params['id']));
    }


    public function del(Request $request)
    {
        // 验证规则
        $rules =  [
            'id' => 'required|max:10'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error('参数验证输错',$validator->errors());
        }
        $params = $request->input();
        $data = Folder::find($params['id']);
        if (null === $data) {
            return $this->error('ID不存在');
        }
        if ( $data->p_id === 0 && user()->auth !== 2 ) {
            return $this->error('没有权限删除一级菜单');
        }
        Folder::where('id',$params['id'])->update($params);
        return $this->success('删除成功');

    }
}
