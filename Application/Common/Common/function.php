<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/7
 * Time: 13:26
 */
/**
 * @param $passwd   创建密码
 */
function create_password($passwd){
    $passwd = ~md5($passwd);
    return sha1($passwd);
}
/**
 * 上传文件
 */
function uploadFile($file,$path='file')
{
    if (! count($_FILES[$file])) {
        return array(
            'status' => false,
            'msg' => '请上传文件'
        );
    }
    foreach ($_FILES as $file) {
        if ($file['error'] == UPLOAD_ERR_OK) {
            // 取得扩展名
            $extName = strtolower(end(explode('.', $file['name'])));

            $time = microtime(true);
            $time = explode('.', $time);
            $time = date("YmdHis{$time[1]}", $time[0]);

            $filename = $time . '.' . $extName;
            $dest = "Upload/{$path}/" . $filename;
            $result = move_uploaded_file($file['tmp_name'], $dest);
            if ($result){
                return array(
                    'status' => true,
                    'msg' => '上传成功！',
                    'filename' => $filename,
                    'file_path' => $dest
                );
            }
            return array(
                'status' => false,
                'msg' => '上传错误！'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '上传错误！'
            );
        }
    }
}
/**
 * 上传多个文件调用
 * @param unknown $file
 * @param string $path
 */
function uploadFiles($file_data = array(),$path='file'){
 foreach ($file_data as $file) {
        if ($file['error'] == UPLOAD_ERR_OK) {
            // 取得扩展名
            $extName = strtolower(end(explode('.', $file['name'])));

            $time = microtime(true);
            $time = explode('.', $time);
            $time = date("YmdHis{$time[1]}", $time[0]);

            $filename = $time . '.' . $extName;
            $dest = "Upload/{$path}/" . $filename;
            $result = move_uploaded_file($file['tmp_name'], $dest);
            if ($result){
                return array(
                    'status' => true,
                    'msg' => '上传成功！',
                    'filename' => $filename,
                    'file_path' => $dest
                );
            }
            return array(
                'status' => false,
                'msg' => '上传错误！'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '上传错误！'
            );
        }
    }
}
