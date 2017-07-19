<?php

namespace App\Http\Controllers;
use App\Folder;
use App\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use App\Libs\upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommonController extends BaseController
{
    protected $uploads;
    public function __construct(Request $request)
    {
        $host = $request->getHost();
        $protocol = 'http://';
        if (env('APP_ENV') === 'production') {
            $this->uploads = filter_var($request->getHost(), FILTER_VALIDATE_IP) ? $protocol.$host.'/stip/public/' : $protocol.$host.'/' ;
        } else {
            $this->uploads = '127.0.0.1:8000/' ;
        }

    }

    /**
     * md笔记上传图片
     * @return \Illuminate\Http\JsonResponse
     */
    public function mdEditorUpload () {

        if (!isset($_FILES['editormd-image-file'])) {
            return $this->error('参数错误');
        }
        $upload = new upload('editormd-image-file','uploads');
        $param = $upload->uploadFile();
        if ( $param['status'] ) {
            return response()->json(array('success'=>1,'message'=>'上传成功','url'=>$this->uploads.$param['data'],'data'=>[$this->uploads.$param['data']]));
        }
        return response()->json(array('success'=>0,'message'=>'上传失败','url'=>$param['data'],'data'=>$param['data']));

    }

    /**
     * 普通笔记上传图片
     * @return \Illuminate\Http\JsonResponse
     */
    public function wangEditorUpload () {
        if (!isset($_FILES['image-file'])) {
            return $this->error('参数错误');
        }
        $upload = new upload('image-file','uploads');
        $param = $upload->uploadFile();
        if ( $param['status'] ) {
            return response()->json(array('errno'=>0,'message'=>'上传成功','data'=>[$this->uploads.$param['data']]));
        }
        return response()->json(array('errno'=>1,'message'=>'上传失败','data'=>$param['data']));


    }


    /**
     *  中转页面提示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function prompt ()
    {
        if(!empty(session('message')) && !empty(session('url')) && !empty(session('jumpTime'))){
            $data = [
                'message' => session('message'),
                'url' => session('url'),
                'jumpTime' => session('jumpTime'),
                'status' => session('status')
            ];
        } else {
            $data = [
                'message' => '请勿非法访问！',
                'url' => '/',
                'jumpTime' => 3,
                'status' => false
            ];
        }
        return view('common.prompt',['data' => $data]);
    }

}
