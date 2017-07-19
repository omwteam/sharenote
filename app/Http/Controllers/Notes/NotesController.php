<?php

namespace App\Http\Controllers\Notes;
use App\Folder;
use App\Notes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class NotesController extends BaseController
{
    /**
     * 每页显示数量
     */
    protected $pagesize;
    /**
     * 模型
     */
    protected $notesModel;

    public function __construct()
    {
        $this->pagesize = config('page.pagesize');
        $this->notesModel = new Notes();
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
            'title' => 'required',
            'f_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->ajaxError('参数验证输错',$validator->errors());
        }
        $params = $request->input();

        //TODO 验证关联表 某个数据是否存在表数据中
        if (!$this->findFolderId($params['f_id'])) {
            return $this->ajaxError('文件夹ID不存在');
        }
//        DB::connection()->enableQueryLog();
        $count = $this->notesModel->like('title',$params['title'])->where(['f_id'=>$params['f_id']])->count();
//        $log = DB::getQueryLog();
//        dd($log);
//        dump($count);
//        exit();
        if ($count > 0) {
            $params['title'] = $params['title'].'('.$count.')';
        }
        $params['u_id'] = user()->id;

        $data = $this->notesModel->create($params);
        if (null != $data) {
            return $this->ajaxSuccess('新增成功',$data);
        }
        return $this->ajaxError('新增失败');

    }

    /**
     * 获取id下面的笔记
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show (Request $request)
    {
        // 验证规则
        $rules =  [
            'id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->ajaxError('参数验证输错',$validator->errors());
        }

        // 用户提交信息
        $params = $request->input();

        // 判断查询文件夹ID是否存在
        if (!$this->findFolderId($params['id'])) {
            return $this->ajaxError('文件夹ID不存在');
        }

        // 选择排序字段
        $params['field'] = isset($params['field']) ? $params['field'] : 'created_at';

        // 选择排序方式
        $params['order'] = isset($params['order']) ? $params['order'] :  'desc';

        // 分页页码
        $params['page'] = isset($params['page']) ? $params['page'] : 1;

        // 每页数量
        $params['pagesize'] = isset($params['pagesize']) ? $params['pagesize'] : $this->pagesize;
        // 起始页
        $start = $params['page'] == 1 ? 0 : ($params['page']-1)*$params['pagesize'];

        // 获取用户ID
        // TODO 私人可见笔记待做 查询数据

        $list = $this->notesModel->isPrivate()->where([ 'f_id'=>$params['id'] ])
                    ->join('users','notes.u_id','=','users.id')
                    ->select('notes.*', 'users.name as author')
                    ->orderBy($params['field'],$params['order'])
                    ->skip($start)
                    ->take($params['pagesize'])
                    ->get()->toArray();

        $totalPage = ceil($this->notesModel->count()/$params['pagesize']);
        return $this->ajaxSuccess('获取数据成功',array('totalPage'=>$totalPage,'data'=>$list));
    }

    /**
     * 获取笔记详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function find (Request $request)
    {
        $id = $request->input('id');
        $data = Notes::find($id);
        if (null != $data) {
            return $this->ajaxSuccess('获取成功',$data);
        }
        return $this->ajaxError('数据不存在');

    }

    /**
     * 更新笔记
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update (Request $request)
    {
        // 验证
        $rules =  [
            'id' => 'required',
            'f_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->ajaxError('参数验证输错',$validator->errors());
        }

        $params = $request->input();
        if (isset($params['title'])) {
            $count = $this->notesModel->like('title',$params['title'])
                ->where(['f_id'=>$params['f_id']])
                ->where('id','!=',$params['id'])
                ->count();
            if ($count > 0) {
                $params['title'] = $params['title'].'('.($count+1).')';
            }
        }


        $params['updated_id'] = user()->id;

        $data = $this->notesModel->where(array('id'=>$params['id'],'f_id'=>$params['f_id']))->update($params);
        if ($data != 1) {
            return $this->ajaxError('更新失败，数据不存在');
        }
        return $this->ajaxSuccess('更新成功',Notes::find($params['id']));
    }

    /**
     * 删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function del (Request $request)
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

        $data = Notes::where('id',$params['id'])->update(array('active'=>'0','updated_id'=>Auth::id()));

        if ($data != 1) {
            return $this->ajaxError('数据在另一端已经被删除了，请刷新页面');
        }
        return $this->ajaxSuccess('删除成功');

    }

    /**
     * 最新文档
     * @return \Illuminate\Http\JsonResponse
     */
    public function latest ()
    {
        $latest = $this->notesModel->isPrivate()
            ->join('users','notes.u_id','=','users.id')
            ->select('notes.*', 'users.name as author')
            ->orderBy('updated_at','desc')
            ->limit($this->pagesize)
            ->get()->toArray();
        return $this->ajaxSuccess('获取成功',$latest);
    }

    /**
     * 搜索
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        // 验证规则
        $rules =  [
            'keywords' => 'required|max:80',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->ajaxError('参数验证输错',$validator->errors());
        }
        $params = $request->input();

        // 选择排序字段
        $params['field'] = isset($params['field']) ? $params['field'] : 'created_at';

        // 选择排序方式
        $params['order'] = isset($params['order']) ? $params['order'] :  'desc';

        // 分页页码
        $params['page'] = isset($params['page']) ? $params['page'] : 1;

        // 每页数量
        $params['pagesize'] = isset($params['pagesize']) ? $params['pagesize'] : $this->pagesize;

        // 第一步查询出总页数
        $noteModel = $this->notesModel;
        $totalNum = $noteModel->isPrivate()
            ->where('title','like','%'.$params['keywords'].'%')
            ->orWhere('content','like','%'.$params['keywords'].'%')->count();
        $totalPage = ceil($totalNum/$params['pagesize']);

        // 起始页
        $start = $params['page'] == 1 ? 0 : ($params['page']-1)*$params['pagesize'];

        // 查询title页数 如果不足就查询匹配content的内容补足
        $titleCondition = [['title','like','%'.$params['keywords'].'%']];
        $titleCount = $noteModel->getCountByCondition($titleCondition);
        $titlePage = ceil($titleCount/$params['pagesize']);
        $titleData = [];
        if ($params['page'] <= $titlePage) {
            $titleData = $noteModel->where($titleCondition)
                ->offset($start)
                ->orderBy($params['field'],$params['order'])
                ->limit($params['pagesize'])
                ->get()->toArray();
        }

        // 匹配content的数据
        $contentData = [];
        if ($params['page'] >= $titlePage) {
            $initPage = $params['page'] - $titlePage;
            $startContent = $initPage == 0 ? 0 : $initPage*$params['pagesize'];
            $contentData = $noteModel->isPrivate()
                ->where('content','like','%'.$params['keywords'].'%')
                ->orderBy($params['field'],$params['order'])
                ->offset($startContent)
                ->limit($params['pagesize'])
                ->get()->toArray();
        }
        $list = array_merge($titleData,$contentData);
        return $this->ajaxSuccess('搜索成功',['totalPage'=>$totalPage,'data'=>$list]);
    }

    /**
     * 验证文档Id是否存在
     * @param $id
     * @return bool
     */
    private function findFolderId($id)
    {
        return Folder::find($id) ? true : false;
    }
}
