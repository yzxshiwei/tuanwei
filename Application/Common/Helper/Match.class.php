<?php
namespace Common\Helper;
class Match{
    /**
     * 获取比赛列表
     * 添加时间13:07:56
     * 
     * @author zlj
     * @return Ambigous <mixed, boolean, string, NULL, multitype:, unknown, object>
     */
    public function listData($user){
        $matchModel = new \Common\Model\MatchModel();
        $userModel = new \Common\Model\UsersModel();
        $where = array();
        $matchModel_l = $matchModel;
        $matchModel->distinct(true)
        ->field("match.id,match.name,match.project_start_time,match.sign_start_time,match.state,match.project_id");
		
        if ($user['user_type'] == $userModel::TYPE_JUDGES){
            $where['j.judge_id'] = $user['user_id'];
            $matchModel->join("judges as j on (match.id = j.project_id)",'left')->where($where);
        }
        $count =  $matchModel->count();
        $Page = new \Think\Page($count,12);
        $page_show = $Page->show();
        
        $matchModel_l
        ->distinct(true)
        ->field("match.id,match.name,match.project_start_time,match.sign_start_time,match.state,match.project_id");
        if ($user['user_type'] == $userModel::TYPE_JUDGES){
            $where['j.judge_id'] = $user['user_id'];
            $matchModel_l->join("judges as j on (match.id = j.project_id)",'left')->where($where);
        }

        $list_data =$matchModel_l->limit($Page->firstRow.','.$Page->listRows)->select();
        return array('Page' => $page_show , 'list_data' => $list_data);
    }
}