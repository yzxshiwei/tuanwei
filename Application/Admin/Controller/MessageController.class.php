<?php
namespace Admin\Controller;
class MessageController extends Controller{
    /**
     * 团队消息
     * 添加时间14:11:31
     * 
     * @author yzx
     */
    public function teamcomment() {
        $post_data = array();
        $post_data['content'] = I("post.content",null,'string');
        $post_data['team_id'] = I('post.team_id',0,'intval');
        if ($post_data['content'] == null || $post_data['team_id'] == 0){
            $this->ajaxReturn(array('status'=>0,'msg'=> "发送失败"));
        }
        $messageModel = new \Common\Model\MessageModel();
        $result = $messageModel->send($post_data, $messageModel::TYPE_TEAM, $this->user['user_id']);
        if ($result['status']) {
            $this->ajaxReturn(array('status'=>1,'msg'=>'发送成功'));
        }else {
            $this->ajaxReturn(array('status'=>0,'msg'=>$result['msg']));
        }
    }
    /**
     * 获取团队交流信息
     * 添加时间2016-2-25
     * 
     * @author yzx
     */
    public function teammsg() {
        $user_id = I('post.team_id',0,'intval');
        $messageModel = new \Common\Model\MessageModel();
        $rersult = $messageModel->getMessage($user_id,$messageModel::TYPE_TEAM);
        $this->ajaxReturn($rersult);
    }
}