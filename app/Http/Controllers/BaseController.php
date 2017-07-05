<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * 返回数据格式
     * @var array
     */
    protected $response = ['code' => 200,'msg'=> '请求成功','data'=>null];

    /**
     * 成功返回
     * @param string $msg
     * @param null $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($msg = '请求成功',$data = null,$code = 200)
    {
        $this->response['code'] = $code;
        $this->response['msg'] = $msg;
        $this->response['data'] = $data;
        return response()->json($this->response);
    }

    /**
     * 错误返回
     * @param string $msg
     * @param null $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($msg = '请求成功',$data = null,$code = 403)
    {
        $this->response['code'] = $code;
        $this->response['msg'] = $msg;
        $this->response['data'] = $data;
        return response()->json($this->response);
    }

    /**
     * 设置code
     * @param int $code
     * @return $this
     */
    public function setCode($code = 200)
    {
        $this->response['code'] = $code;
        return $this;
    }
}
