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
function uploadFile($file,$path='file'){
	
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
            $time = date("YmdHis{$time[1]}", $time[0]).rand(1,1000);

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
 * 单文件上传
 */
 function Upload($upfile,$pass="file"){
     
 	  if(is_uploaded_file($upfile['tmp_name'])){
 	  	
			//获取数组里面的值 
			$name=$upfile["name"];//上传文件的文件名 
			$tmp_name=$upfile["tmp_name"];//上传文件的临时存放路径 
			
			$extName = strtolower(end(explode('.', $name)));//文件后缀名
			$time = microtime(true);
            $time = explode('.', $time);
            $time = date("YmdHis{$time[1]}", $time[0]).rand(1,1000);
            $filename = $time . '.' . $extName;
			$dest = "Upload/$pass/" . $filename;
            $result = move_uploaded_file($tmp_name, $dest);
			
			if ($result){

                return array(
                    'status' => true,
                    'msg' => '上传成功！',
                    'filename' => $filename,
                    'file_path' => $dest
                );
            }else{
	            return array(
	                'status' => false,
	                'msg' => '上传错误！'
	            );
            }
		  }else{
				return array(
					'status' => false,
					'msg' => '上传错误！'
				);
		  }
 }
 


function v_dump($arr){
	echo "<pre>";
	var_dump($arr);
	echo "</pre>";
}

