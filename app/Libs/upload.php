<?php
namespace App\Libs;

class upload
{
    protected $fileName;
    protected $maxSize;
    protected $allowMime;
    protected $allowExt;
    protected $uploadPath;
    protected $imgFlag;
    protected $fileInfo;
    protected $error;
    protected $ext;
    protected $uploadStatus;

    /**
     * upload constructor.
     * @param string $fileName
     * @param string $uploadPath
     * @param bool $imgFlag
     * @param int $maxSize
     * @param array $allowExt
     * @param array $allowMime
     * @param bool $uploadStatus
     */
    public function __construct($fileName = 'myFile', $uploadPath = '/uploads', $imgFlag = true, $maxSize = 5242880, $allowExt = array('jpeg', 'jpg', 'png', 'gif'), $allowMime = array('image/jpeg', 'image/png', 'image/gif'),$uploadStatus = false)
    {
        $this->fileName = $fileName;
        $this->maxSize = $maxSize;
        $this->allowMime = $allowMime;
        $this->allowExt = $allowExt;
        $this->uploadPath = $uploadPath;
        $this->imgFlag = $imgFlag;
        $this->fileInfo = $_FILES[$this->fileName];
        $this->uploadStatus = $uploadStatus;
    }

    /**
     * check error
     * @return boolean
     */
    protected function checkError()
    {
        if (!is_null($this->fileInfo)) {
            if ($this->fileInfo['error'] > 0) {
                switch ($this->fileInfo['error']) {
                    case 1:
                        $this->error = '超过了PHP配置文件中upload_max_filesize选项的size';
                        break;
                    case 2:
                        $this->error = '超过了表单中MAX_FILE_SIZE设置的';
                        break;
                    case 3:
                        $this->error = '文件部分被上�?';
                        break;
                    case 4:
                        $this->error = '没有选择上传文件';
                        break;
                    case 6:
                        $this->error = '没有找到临时目录';
                        break;
                    case 7:
                        $this->error = '文件不可用';
                        break;
                    case 8:
                        $this->error = '由于PHP的扩展程序中断文件上传';
                        break;

                }
                return false;
            } else {
                return true;
            }
        } else {
            $this->error = '文件上传出错';
            return false;
        }
    }

    /**
     * �?测上传文件的大小
     * @return boolean
     */
    protected function checkSize()
    {
        if ($this->fileInfo['size'] > $this->maxSize) {
            $this->error = '上传文件过大';
            return false;
        }
        return true;
    }

    /**
     * �?测扩展名
     * @return boolean
     */
    protected function checkExt()
    {
        $this->ext = strtolower(pathinfo($this->fileInfo['name'], PATHINFO_EXTENSION));
        if (!in_array($this->ext, $this->allowExt)) {
            $this->error = '不允许的扩展文件';
            return false;
        }
        return true;
    }

    /**
     * 检测文件的类型
     * @return boolean
     */
    protected function checkMime()
    {
        if (!in_array($this->fileInfo['type'], $this->allowMime)) {
            $this->error = '不允许的文件类型';
            return false;
        }
        return true;
    }

    /**
     * 检测是否是真实图片
     * @return boolean
     */
    protected function checkTrueImg()
    {
        if ($this->imgFlag) {
            if (!@getimagesize($this->fileInfo['tmp_name'])) {
                $this->error = '不是真实图片';
                return false;
            }
            return true;
        }
    }

    /**
     * 检测是否是POST方法上传文件
     * @return boolean
     */
    protected function checkHTTPPost()
    {
        if (!is_uploaded_file($this->fileInfo['tmp_name'])) {
            $this->error = '文件不是通过HTTP POST方式上传上来的';
            return false;
        }
        return true;
    }

    /**
     *显示错误
     */
    protected function showError()
    {
        exit('<span style="color:red">' . $this->error . '</span>');
    }

    /**
     * 检测创建目录
     */
    protected function checkUploadPath()
    {
        if (!file_exists($this->uploadPath)) {
            mkdir($this->uploadPath, 0777, true);
        }
    }

    /**
     * 产生唯一字符串
     * @return string
     */
    protected function getUniName()
    {
        return md5(uniqid(microtime(true), true));
    }

    /**
     * @param $data
     * @return array
     */
    protected function response ($data)
    {
        return array('status'=>$this->uploadStatus,'data'=>$data);
    }

    /**
     * 上传文件
     * @return array
     */
    public function uploadFile()
    {
        if (!($this->checkError() && $this->checkSize() && $this->checkExt() && $this->checkMime() && $this->checkTrueImg() && $this->checkHTTPPost())) {
            return $this->response($this->error);
        }

        $this->checkUploadPath();
        $this->uniName = $this->getUniName();
        $this->destination = $this->uploadPath . '/' . $this->uniName . '.' . $this->ext;
        $moveStatus = @move_uploaded_file($this->fileInfo['tmp_name'], $this->destination);
        if (!$moveStatus) {
            return $this->response($this->error = '文件移动失败');
        }
        $this->uploadStatus = true;
        return $this->response($this->destination);
    }
}



