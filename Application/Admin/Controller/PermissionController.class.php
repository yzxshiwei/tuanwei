<?php
namespace Admin\Controller;
/**
 * 用户权限控制器
 * 添加时间2016-2-26
 * 
 * @author yzx
 *
 */
class PermissionController extends Controller{
    /**
     * 添加分组
     * 添加时间2016-2-26
     * 
     * @author yzx
     */
    public function addgroup() {
        if (IS_POST){
            $post_data = array();
            $post_data['group_name'] = I('post.name',null,'string');
            if ($post_data['group_name'] == null){
                $this->error('分组名称不能为空');
            }
            $groupModel = D('group');
            $result = $groupModel->add($post_data);
            if ($result){
                $this->success('添加成功',U('Permission/grouplist'));
            }else {
                $this->error('添加失败');
            } 
        }else {
            $this->display();
        }
    }
    /**
     * 分组列表
     * 添加时间2016-2-26
     * 
     * @author yzx
     */
    public function grouplist() {
        $groupModel = new \Common\Model\GroupModel();
        $reesult = $groupModel->listData();
        $this->assign('list_data',$reesult['list_data']);
        $this->assign('Page' , $reesult['Page']);
        $this->display();
    }
    /**
     * 添加权限
     * 添加时间2016-2-26
     * 
     * @author yzx
     */
    public function addpermission() {
        $PermissionModel = new \Common\Model\PermissionModel();
        if (IS_POST){
            $author = I('post.author');
            $group_id = I('post.group_id',0,'intval');
            $result = $PermissionModel->addData($group_id, $author);
            if ($result){
                $this->success('添加成功',U('Permission/grouplist'));
            }else {
                $this->error('添加失败');
            }
        }else {
            $id = I("id",0,'intval');
            $groupModel = D('group');
            $author = $PermissionModel->where(array('group_id' => $id))->select();
            $group_data = $groupModel->where(array('id'=>$id))->find();
            $permission = C('PERMISSION');
            $user_author = array();
            foreach ($author as $k => $v){
                $user_author[] = $v['permission'];
            }
            $output_data = array();
            foreach ($permission as $k => $v){
                if (in_array($k, $user_author)){
                    $permission[$k] = array('checked' =>'checked="checked"' ,'name'=>$v['name']);
                }else {
                    $permission[$k] = array('checked' =>'' ,'name'=>$v['name']);
                }
            }
            $this->assign('list_data',$permission);
            $this->assign('group_data',$group_data);
            $this->display();
        }
    }
    /**
     * 分组删除
     * 添加时间2016-2-29
     * 
     * @author yzx
     */
    public function delete(){
        $group_id = I('id',0,'intval');
        $groupModel = new \Common\Model\GroupModel();
        $result = $groupModel->deleteGroup($group_id);
        if($result){
           $this->ajaxReturn(array('status'=>1,'msg'=>'删除成功')); 
        }else {
            $this->ajaxReturn(array('status'=>0,'msg'=>'失败'));
        }
    }
    /**
     * 用户添加分组
     * 添加时间2016-2-26
     * 
     * @author yzx
     */
    public function usergroup() {
        $userModel = new \Common\Model\UsersModel();
        if (IS_POST){
            $post_data = array();
            $user_id = I("post.user_id",0,'intval');
            $post_data['group_id'] = I("post.group_id",0,'intval');
			$post_data['user_type'] = I("post.group_id",0,'intval');
			$userModel->startTrans();
            $result = $userModel->where(array('user_id' => $user_id))->save($post_data);
            if ($result){
                if($post_data['group_id'] == '6')
                $student = M('student');
                $data = array('user_id' => $user_id);
                if($student->add($data)){
                    $student->commit();
                    $this->success('添加成功',U('Index/index'));
                }
                else{
                    $student->rollback();
                    $this->error('添加失败');
                }
                
                
            }else {
                $student->rollback();
                $this->error('添加失败');
            }
        }else {
            $user_id = I('user_id',0,'intval');
            $groupModel = D('group');
            $group_list = $groupModel->select();
            $result = $userModel->where(array('user_id'=>$user_id))->find();
            $this->assign('data',$result);
            $this->assign('gorup_list',$group_list);
            $this->display();
        }
       
    }
}