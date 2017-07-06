<?php

namespace App\Http\Controllers;
use App\Folder;
use App\Notes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class NotesController extends BaseController
{
    public function index()
    {
        return Notes::all();
    }


    /**
     * 新增笔记
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add (Request $request)
    {
        // 验证规则
        $rules =  [
            'title' => 'required|max:30',
            'f_id' => 'required|max:11'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors(),'参数验证输错');
        }
        $params = $request->input();
        //TODO 验证关联表 某个数据是否存在表数据中
        if (!$this->findFolderId($params['f_id'])) {
            return $this->error('文件夹ID不存在');
        }

        $count = Notes::where(['f_id'=>$params['f_id'],'title'=>$params['title'],'active'=>'1'])->count();

        if ($count > 0) {
            return $this->error('标题重复');
        }
        $params['u_id'] = user()->id;

        $data = Notes::create($params);
        return $this->success('新增成功',$data);

    }

    public function show (Request $request)
    {
        // 验证规则
        $rules =  [
            'id' => 'required|max:11'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors(),'参数验证输错');
        }

        // 用户提交信息
        $params = $request->input();

        // 判断查询文件夹ID是否存在
        if (!$this->findFolderId($params['id'])) {
            return $this->error('文件夹ID不存在');
        }

        // 选择排序字段
        if (!isset($params['field'])) {
            $params['field'] = 'created_at';
        }

        // 选择排序方式
        if (!isset($params['order'])) {
            $params['order'] = 'asc';
        }
        // 分页页码
        if (!isset($params['page'])) {
            $params['page'] = 1;
        }
        // 每页数量
        if (!isset($params['pagesize'])) {
            $params['pagesize'] = 3;
        }
        // 起始页
        $start = $params['page'] == 1 ? 0 : ($params['page']-1)*$params['pagesize'];

        // 获取用户ID
        $userId = user()->id;
        // TODO 私人可见笔记待做 查询数据
        $data = Notes::where(['f_id'=>$params['id'],'active'=>'1'])
                    ->orderBy($params['field'],$params['order'])
                    ->offset($start)
                    ->limit($params['pagesize'])
                    ->get();
        return $this->success('获取数据成功',$data);
    }

    public function find (Request $request)
    {
        $id = $request->input('id');
        $data = Notes::find($id);
        return $this->success('获取成功',$data);

    }

    public function update (Request $request)
    {
        // 验证
        $rules =  [
            'id' => 'required|max:10',
            'f_id' => 'required|max:10'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error('参数验证输错',$validator->errors());
        }
        $params = $request->input();
        if (isset($params['title'])) {
            $exist = Notes::where(array('title'=>$params['title'],'f_id'=>$params['f_id']))->count();
            if ($exist > 0) {
                return $this->error('笔记名称重复');
            }
        }

        $params['updated_id'] = user()->id;

        $data = Notes::where(array('id'=>$params['id'],'f_id'=>$params['f_id']))->update($params);
        if ($data != 1) {
            return $this->success('更新失败');
        }
        return $this->success('更新成功',Notes::find($params['id']));
    }
    public function del (Request $request)
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

        $data = Notes::where('id',$params['id'])->update(array('active'=>'0'));
        if ($data != 1) {
            return $this->success('删除失败');
        }
        return $this->success('删除成功');

    }
    public function latest ()
    {
        $latest = Notes::where(array('active'=>'1'))->get();
        return $this->success('获取成功',$latest);
    }
    private function findFolderId($id)
    {
        return Folder::find($id) ? true : false;
    }
}
