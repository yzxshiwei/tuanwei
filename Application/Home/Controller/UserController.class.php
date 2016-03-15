<?php
/**
 * 用户控制表
 * 添加时间2016-2-16
 * @author yzx
 */
namespace Home\Controller;
class UserController extends \Common\Helper\Controller{
    /**
     * 用户注册
     * 添加时间2016-2-16
     * 
     * @author yzx
     */
    public function register(){
        if (IS_POST){
            $userModel = new \Common\Model\UsersModel();
            $input_data = array();
            $input_data['email'] = I('post.email','','string');
            $input_data['card_id'] = I('post.card_id',0,'string');
			$input_data['card_degree'] = I('post.card_degree',0,'string');
            $input_data['user_type'] = \Common\Model\UsersModel::TYPE_STUDENT;
            $input_data['sex'] = I('post.sex',0,'intval');
            $input_data['passwd'] = I('post.passwd',''.'string');
            $input_data['college'] = I('post.college','','string');
            $input_data['user_name'] = I('post.user_name','','string');
            $input_data['nation'] = I('post.nation','','string');
            $input_data['birth'] = I('post.birth','','string');
			$input_data['enter_year'] = I('post.enter_year','','string');
            $input_data['major'] = I('post.major',0,'intval');
            $input_data['degree'] = I('post.degree',0,'intval');
			$input_data['group_id'] = $input_data['user_type'];
			$input_data['card_type'] = 2; // 2 代表身份证
			$input_data['state'] = 1;
            if ($input_data['user_name'] == ''){
                $input_data['user_name'] = $input_data['email'];
            }
			
            $result = $userModel->addUser($input_data);
            if ($result['status']){

                $this->success('注册成功',U('Index/index'));
            }else {
                $this->error($result['msg']);
            }
        }else {
            $major = \Common\Model\UsersModel::$major;
            $degree = \Common\Model\UsersModel::$degree;
            $this->assign('major',$major);
            $this->assign('degree',$degree);
            $this->display();
        }
    }
    /**
     * 发送验证码
     * 添加时间2016-2-25
     *
     * @author yzx
     */
    public function sendcode() {
        $email = I("post.email",'','string');
        $user = new \Common\Helper\User();
        $code = $user->rand_code($email);
        $result = $user->send_email($email , $code);
        if ($result == true){
            $this->success('发送邮件成功，请注意查收');
        }else {
            $this->error('发送验证码失败，请检查邮箱是否正确');
        }
    }
    /**
     * 找回密码
     * 添加时间2016-2-25 10:36:12
     *
     * @author yzx
     */
    public function rtepwd(){
        if (IS_POST){
            $password = I('post.password','','string');
            $email = I('post.email','','string');
            $user = new \Common\Helper\User();
            $result = $user->updatepwd($password , $email);
            if ($result){
                $this->success('修改密码成功',U('Login/index'));
            }else {
                $this->error('修改失败');
            }
        }else {
            $userModel = new \Common\Model\UsersModel();
            $sessioncode  = I('sessioncode',null,'string');
            $email = I('email',null,'string');
            $session_code = session($email);
            $user_data = $userModel->where(array('email' => $email))->find();
            if (empty($user_data)){
                $this->error('邮箱错误',U('Login/index'));
            }
            if ($sessioncode == null || $email == null){
                $this->error('参数错误',U('Login/index'));
            }
            if ($sessioncode != $session_code){
                $this->error('参数错误',U('Login/index'));
            }
            $this->assign('email',$email);
            $this->display();
        }
    }
}