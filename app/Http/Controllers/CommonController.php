<?php

namespace App\Http\Controllers;
use Dompdf\Dompdf;
use App\Libs\upload;
use Illuminate\Http\Request;

class CommonController extends BaseController
{


    public function mdEditorUpload () {
        if (!isset($_FILES['editormd-image-file'])) {
            return $this->error('参数错误');
        }
        $upload = new upload('editormd-image-file','uploads');
        $param = $upload->uploadFile();
        if ( $param['status'] ) {
            return response()->json(array('success'=>1,'message'=>'上传成功','url'=>$param['data'],'data'=>[$param['data']]));
        }
        return response()->json(array('success'=>0,'message'=>'上传失败','url'=>$param['data'],'data'=>$param['data']));


    }
    public function wangEditorUpload () {
        if (!isset($_FILES['image-file'])) {
            return $this->error('参数错误');
        }
        $upload = new upload('image-file','uploads');
        $param = $upload->uploadFile();
        if ( $param['status'] ) {
            return response()->json(array('errno'=>0,'message'=>'上传成功','data'=>[$param['data']]));
        }
        return response()->json(array('errno'=>1,'message'=>'上传失败','data'=>$param['data']));


    }

    public function export (Request $request)
    {
        $params = $request->input();
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($params['content']);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();


        // Output the generated PDF to Browser
        $dompdf->stream();
    }
}
