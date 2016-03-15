<?php
namespace Admin\Controller;
/**
 * 后台用户控制器
 * 添加时间10:42:08
 * 
 * @author yzx
 *
 */
class UserController extends Controller{
    public function logout() {
        \Common\Helper\RunUser::newInstantiation()->signOut();
        $this->success('退出成功',U('Home/Index/index'));
    }
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
			$card_type = I('post.card_type',0,'intval');
			
            $input_data['email'] = I('post.email','','string');

            $input_data['sex'] = I('post.sex',0,'intval');
            $input_data['passwd'] = I('post.password',''.'string');
            $input_data['college'] = I('post.college','','string');
            $input_data['user_name'] = I('post.user_name','','string');
            $input_data['nation'] = I('post.nation','','string');
            $input_data['major'] = I('post.major',0,'intval');
			$input_data['user_type'] =  I('post.user_type',0,'string');
			$input_data['group_id'] = $input_data['user_type'];
			$input_data['state'] = 1;
	
			if($card_type==1){
				$input_data['card_id'] = I('post.card_id',0,'string');
			}elseif($card_type==2){
				$input_data['card_degree'] = I('post.card_id',0,'string');
			}
            $result = $userModel->addUser($input_data);
			
            if ($result['status']){
                $this->success('注册成功',U('Index/usermanage'));
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
     * 根据ID获取用户数据
     * 添加时间数据
     * 
     * @author yzx
     * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, object>
     */
    public function user() {
        $user_id = I('post.team_id',0,'intval');
        $userModel = new \Common\Model\UsersModel();
        $user_data = $userModel->where(array('user_id' => $user_id))->find();
        return $user_data;
    }
	
	/**
     * 根据ID获取用户数据
     * 2016-02-24
     * 
     * @author zlj
     * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, object>
     */
    public function find_user() {
        $user_id = I('user_id');
        $userModel = new \Common\Model\UsersModel();
        $user_data = $userModel->join('students on students.user_id=users.user_id')->where(array('users.user_id' => $user_id))->find();
		
		$this->ajaxReturn($user_data);
    }
}