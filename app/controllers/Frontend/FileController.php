<?php namespace Frontend;

use \Input;
use \Redirect;

class FileController extends \Controller {
    /**
     * 文件上传
     *
     * @return Response
     */
    public function postUpload() {
        header('Access-Control-Allow-Origin:*');

        /*$referer = $_SERVER['HTTP_REFERER'];
        if (empty($referer)) {
            throw new Exception("非法访问！", 1);
        }*/

        $output = array('err' => 0, 'msg' => '', 'src' => '');

        $file = Input::file('file');
        if ($file) {
             $tmpName = $file->getFileName();
             $ext = $file->getClientOriginalExtension();
             $fileName =  md5($tmpName) . uniqid() .'.'. $ext;
             $movePath = 'storage/uploads/';
             $path = $file->move($movePath, $fileName);
             //
             $output['src'] = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $movePath . $fileName;
        }
        else {
            $output['err'] = 1;
            $output['msg'] = '无上传文件';
        }

        //output json;
        exit(json_encode($output));
    }
}