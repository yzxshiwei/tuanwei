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
            $input_data['card_type'] = I('post.card_type','string');
            $input_data['card_id'] = I('post.card_id',0,'intval');
            $input_data['user_type'] = I('post.user_type',0,'intval');
            $input_data['sex'] = I('post.sex',0,'intval');
            $input_data['passwd'] = I('post.passwd',''.'string');
            $input_data['college'] = I('post.college','','string');
            $input_data['user_name'] = I('post.user_name','','string');
            $input_data['nation'] = I('post.nation','','string');
            $input_data['birth'] = I('post.birth','','string');
            $input_data['major'] = I('post.major',0,'intval');
            $input_data['degree'] = I('post.degree',0,'intval');
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
}