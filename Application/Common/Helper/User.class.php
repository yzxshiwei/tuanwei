<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/14
 * Time: 20:25
 */
namespace Common\Helper;

/**
 * Class User   用户助手
 * @package Common\Helper
 */
class User{

    /**
     * @param $usertype 取得用户附加模型
     * @return \Model|null|\Think\Model
     */
    protected function _getExModel($usertype){
        $tables = array(
            \Common\Model\UsersModel::TYPE_TEACHER => "teachers",//教师
            \Common\Model\UsersModel::TYPE_STUDENT => "students",//学生
        );
        if(!isset($tables[$usertype])){
            return null;
        }
        return D(ucwords($tables[$usertype]));
    }

    /**
     * 取得用户信息
     * @param $userid
     * @return array|bool|mixed
     */
    public function getInfo($userid){
        $info = $this->getBaseInfo($userid);
        if(!$info){
            return false;
        }
        $exModel = $this->_getExModel($info["user_type"]);
        if(!$exModel){
            return $info;
        }
        $exInfo = $exModel->where(array("user_id"=>$userid))->find();
        return array_merge($info,$exInfo?$exInfo:array());
    }

    /**
     * 取得用户基本信息
     * @param $userid
     * @return bool|mixed
     */
    public function getBaseInfo($userid){
        $userModel = D("Users");
        $info = $userModel->where(array("user_id"=>$userid))->find();
        return $info ? $info : false;
    }

    /**
     * 添加用户
     * @param array $data
     * @return bool|mixed
     */
    public function add(array $data){
        $necessary = array(//必须的字段
            "user_name",
            "user_type",
            "passwd"
        );
        if(count(array_intersect_key(array_fill_keys($necessary,null),$data)) < count($necessary)){
            return false;
        }

        $userModel = D("Users");
        $baseFields = $userModel->getDbFields();
        $baseFields = array_fill_keys(array_keys($baseFields),null);
        $baseValues = array_merge($baseFields,array_intersect_key($data,$baseFields));
        $baseValues = array_filter($baseValues,function($value){return $value !== null;});

        $userModel->startTrans();
        $userId = $userModel->add($baseValues);
        if(!$userId){
            $userModel->rollback();
            return false;
        }

        $exModel = $this->_getExModel($data["user_type"]);
        if(!$exModel){
            $userModel->commit();
            return $userId;
        }
        $exFields = $exModel->getDbFields();
        $exFields = array_fill_keys(array_keys($exFields),null);
        $exValues = array_merge($exFields,array_intersect_key($data,$exFields));
        $exValues = array_filter($exValues,function($value){return $value !== null;});
        if($exValues && $exModel->add($exValues)){
            $userModel->commit();
            return $userId;
        }
        $userModel->rollback();
        return false;
    }

    /**
     * 保存用户
     * @param $userid
     * @param array $data
     * @return bool
     */
    public function save($userid,array $data){
        $userModel = D("Users");
        $info = $this->getInfo($userid);
        if(!$info){
            return false;
        }

        $baseFields = $userModel->getDbFields();
        $baseFields = array_fill_keys(array_keys($baseFields),null);
        $baseValues = array_merge($baseFields,array_intersect_key($data,$baseFields));
        $baseValues = array_filter($baseValues,function($value){return $value !== null;});

        $userModel->startTrans();
        if($baseValues && !$userModel->where(array("user_id"=>$userid))->save($baseValues)){
            $userModel->rollback();
            return false;
        }

        $exModel = $this->_getExModel($info["user_type"]);
        if(!$exModel){
            $userModel->commit();
            return true;
        }
        $exFields = $exModel->getDbFields();
        $exFields = array_fill_keys(array_keys($exFields),null);
        $exValues = array_merge($exFields,array_intersect_key($data,$exFields));
        $exValues = array_filter($exValues,function($value){return $value !== null;});
        if($exValues && !$exModel->where(array("user_id"=>$userid))->save($exValues)){
            $userModel->rollback();
            return false;
        }
        $userModel->commit();
        return true;
    }
    /**
     * 获取全部用户数据列表
     * 添加时间15:54:13
     * 
     * @author yzx
     * @return multitype:string Ambigous <mixed, boolean, string, NULL, multitype:, unknown, object>
     */
    public function getUserList(){
        $userModel = new \Common\Model\UsersModel();
        $count = $userModel->count();
        $Page = new \Think\Page($count,12);
        $page_show = $Page->show();
        $list_data = $userModel->limit($Page->firstRow.','.$Page->listRows)->select();
        if (!empty($list_data)){
            foreach ($list_data as $k => $v){
                $list_data[$k]['user_type'] = $userModel::$user_type[$v['user_type']];
            }
        }
        return array('Page' => $page_show,'list_data' => $list_data);
    } 
    /**
     * 发送邮件
     * @param string $email
     * @param string $content
     * @return boolean
     */
    public function send_email($email,$content) {
        Vendor('PHPMailer.Phpmailer');
        $mail = new \PHPMailer();
        
        $mail->IsSMTP();
        $mail->CharSet ="UTF-8";//编码
        $mail->Debugoutput = 'html';// 支持HTML格式
        $mail->Host = C('MAIL_HOST');//HOST 地址
        $mail->Port = 25;//端口
        $mail->SMTPAuth = C('MAIL_SMTPAUTH');
        $mail->Username = C('MAIL_USERNAME');//用户名
        $mail->Password = C('MAIL_PASSWORD');//密码
        $mail->SetFrom(C('MAIL_USERNAME'),'团委');//发件人地址, 发件人名称
        $mail->AddAddress($email);//收信人地址
        $mail->Subject = '验证码';//邮件标题
        $mail->MsgHTML($content);
        return $mail->Send();
    }
    /**
     * 生成邮箱验证码
     * 添加时间2016-2-25 11:52:41
     *
     * @author yzx
     */
    public function rand_code($email){
        $string = \Org\Util\String::randString(6);
        session($email,$string);
        return $string;
    }
    /**
     * 找回密码
     * 添加时间2016-2-25
     * 
     * @author yzx
     * @param string $pwd
     * @param string $code
     * @param string $email
     * @return bool
     */
    public function updatepwd($pwd,$code,$email) {
        $userModel = new \Common\Model\UsersModel();
        $save_data = array();
        $session_code = session($email);
        if ($code == $session_code){
            $save_data['passwd'] = create_password($pwd);
           $result = $userModel->where(array('email' => $email))->save($save_data);
           if ($result){
               session($email,null);
               return true;
           }
           return false;
        }
        return false;
    }
}