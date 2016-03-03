<?php
namespace Common\Helper;
class Message{
    /**
     * 获取消息列表除了团队消息
     * 添加时间2016-3-1
     * 
     * @author tzx
     * @param array $user
     * @return Ambigous <NULL, string, mixed, boolean, multitype:, unknown, object>
     */
    public function listData($user){
        $messageModel = new \Common\Model\MessageModel();
        $userModel = new \Common\Model\UsersModel();
        $where['to_user'] = $user['user_id'];
        $where['msg_type'] = array('neq','team');
        $where['read_time'] = array('eq',0);
        $msg_list = $messageModel->where($where)->select();
        if (is_array($msg_list) && !empty($msg_list)){
            foreach ($msg_list as $k => $v){
                if ($v['msg_type'] == $messageModel::TYPE_JUDGES_PROJECT || 
                    $v['msg_type'] == $messageModel::TYPE_TEACHER_PROJECT ||
                    $v['msg_type'] == $messageModel::TYPE_USER_PROJECT){
                    $msg_list[$k]['agree_url'] = U('Index/agree',array('id'=>$v['id'],'proid' => $v['project_id']));
                    $msg_list[$k]['refuse_url'] = U('Index/refuse',array('id'=>$v['id'],'proid' => $v['project_id']));
                    $msg_list[$k]['is_sys'] = 1;
                }else {
                    $msg_list[$k]['is_sys'] = 0;
                    $msg_list[$k]['read_url'] = U('Index/read',array('id'=>$v['id']));
                }
            }
        }
        return $msg_list;
    }
}