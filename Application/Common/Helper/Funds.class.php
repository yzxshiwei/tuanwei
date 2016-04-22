<?php
namespace Common\Helper;
class Funds{
    /**
     * 获取比赛列表
     * 添加时间13:07:56
     * 
     * @author zlj
     * @return Ambigous <mixed, boolean, string, NULL, multitype:, unknown, object>
     */
    public function listData($user){
        $matchModel = new \Common\Model\FundsModel();
        $userModel = new \Common\Model\UsersModel();
        $where = array();
		$field = "funds.id,funds.name,funds.project_start_time,funds.sign_start_time,funds.state,funds.project_id,funds.project_end_time,funds.sign_end_time";
        $matchModel_l = $matchModel;
        $matchModel->distinct(true)
        ->field($field);
        if ($user['user_type'] == $userModel::TYPE_JUDGES){
            $where['j.judge_id'] = $user['user_id'];
			$where['j.state'] = \Common\Model\Funds_judgesModel::STATE_ADOPT;
            $matchModel->join("judges as j on (funds.id = j.project_id)",'left')->where($where);
        }
        $count =  $matchModel->count();
        $Page = new \Think\Page($count,12);
        $page_show = $Page->show();
        
        $matchModel_l
        ->distinct(true)
        ->field($field);
        if ($user['user_type'] == $userModel::TYPE_JUDGES){
            $where['j.judge_id'] = $user['user_id'];
			$where['j.state'] = \Common\Model\Funds_judgesModel::STATE_ADOPT;
            $matchModel_l->join("judges as j on (funds.id = j.project_id)",'left')->where($where);
        }

        $list_data =$matchModel_l->limit($Page->firstRow.','.$Page->listRows)->select();
        return array('Page' => $page_show , 'list_data' => $list_data);
    }
}