<?php

namespace App\Http\Controllers\Notes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Folder;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class FolderController extends BaseController
{
    //
    public function store(Request $request)
    {
        // 验证规则
        $rules =  [
            'title' => 'required|max:80',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->ajaxError('参数验证输错',$validator->errors());
        }
        $params = $request->input();

        // 只有管理员才有权限创建一级菜单
        if (user()->auth != 2 &&  $params['p_id'] == 0) {
            return $this->ajaxError('创建失败，没有权限创建一级菜单');
        }

        $num = Folder::where(['title'=>$params['title'],'p_id'=>$params['p_id']])->count();
        if ($num > 0) {
            return $this->ajaxError('文件夹名称重复');
        }
        $params['u_id'] = user()->id;
        $data = Folder::create($params);
        if (null != $data) {
            return $this->ajaxSuccess('新增成功',$data);
        }
        return $this->ajaxError('新增失败');
    }

    /**
     * @return array
     */
    public function listAll()
    {
        //
        $categories = Folder::where(array('p_id'=>0))->select('id','title','p_id')->get();
        $allCategories = Folder::where([['p_id','>',0]])->select('id','title','p_id','u_id')->get();
        return $this->ajaxSuccess('请求成功',compact('categories','allCategories'));
    }


    public function update(Request $request)
    {
        // 验证规则
        $rules =  [
            'title' => 'required|max:50',
            'id' => 'required|max:11',
            'pid' => 'required|max:11'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->ajaxError('参数验证输错',$validator->errors());
        }
        $params = $request->input();
        if (Auth::user()->auth != 2 && $params['pid'] == 0) {
            return $this->ajaxError('没有权限更改一级菜名称');
        }
        $exist = Folder::where(array('p_id'=>$params['pid'],'title'=>$params['title']))->count();
        if ($exist > 0) {
            return $this->ajaxError('文件夹名称重复');
        }
        $flag = Folder::where('id',$params['id'])->update(array('title'=>$params['title']));
        if (1 != $flag) {
            return $this->ajaxError('修改失败');
        }
        return $this->ajaxSuccess('修改成功',Folder::find($params['id']));
    }


    public function del(Request $request)
    {
        // 验证规则
        $rules =  [
            'id' => 'required|max:10'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->ajaxError('参数验证输错',$validator->errors());
        }

        $params = $request->input();
        $Folder = Folder::find($params['id']);

        if (null === $Folder) {
            return $this->ajaxError('数据不存在');
        }

        if ($Folder->notes()->count() > 0) {
            return $this->ajaxError('删除失败，必须删除文件夹下所有笔记');
        }

        if (Folder::where('p_id',$params['id'])->count() > 0) {
            return $this->ajaxError('删除失败，必须删除该文件夹下面的子文件夹');
        }

        if ( $Folder->p_id === 0 && user()->auth !== 2 ) {
            return $this->ajaxError('删除失败，没有权限删除一级菜单');
        }

        $flag = $Folder->where('id',$params['id'])->update(array('active'=> '0'));
        if (1 != $flag) {
            return $this->ajaxError('删除失败');
        }

        return $this->ajaxSuccess('删除成功');

    }
}
