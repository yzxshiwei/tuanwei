<?php
namespace Common\Helper;
class Project{
    /**
     * 获取项目列表
     * 添加时间13:07:56
     * 
     * @author yzx
     * @return Ambigous <mixed, boolean, string, NULL, multitype:, unknown, object>
     */
    public function listData($user){
        $porjectModel = new \Common\Model\ProjectModel();
        $porjectModel_l = $porjectModel;
        $userModel = new \Common\Model\UsersModel();
        $porjectModel->alias('p')
        ->join('match_project AS mp ON (p.id = mp.project_id)','left')
        ->join('`match` AS m ON(m.id = mp.match_id)','left')
        ->join('project_status AS ps ON(p.id = ps.project_id)','left')
        ->join('team as t ON(t.project_id = p.id)','left');
        if ($user['group_id'] == $userModel::TYPE_MANAGE){
            $where['mp.match_id'] = array('GT',0); 
            $porjectModel->where($where);
        }else {
            $where['t.user_id'] = array('eq',$user['user_id']);
            $porjectModel->where($where);
        }
        $count =  $porjectModel->count();
        $Page = new \Think\Page($count,1);
        $filed = 'p.is_open,p.id,p.creat_id,p.intro,p.name,p.sub_title,m.name AS m_name,m.id AS m_id,ps.score,ps.result,t.user_type';
        if ($user['group_id'] == $userModel::TYPE_TEACHER || $user['group_id'] == $userModel::TYPE_JUDGES){
            $filed = 'p.is_open,p.id,p.creat_id,p.intro,p.name,p.sub_title,m.name AS m_name,m.id AS m_id,ps.score,ps.result';
        }
        $page_show = $Page->show();
        
        $porjectModel_l
        ->field($filed)
        ->alias('p')
        ->join('match_project AS mp ON (p.id = mp.project_id)','left')
        ->join('`match` AS m ON(m.id = mp.match_id)','left')
        ->join('project_status AS ps ON(p.id = ps.project_id)','left');
        if ($user['group_id'] == $userModel::TYPE_TEACHER){
            $porjectModel_l->join('teacher_team as t ON(t.project_id = p.id)','left');
        }elseif($user['group_id'] == $userModel::TYPE_JUDGES) {
            $porjectModel_l->join('judges as t ON(t.project_id = p.id)','left');
        }else {
            $porjectModel_l->join('team as t ON(t.project_id = p.id)','left');
        }
        $where  = array();
        if ($user['group_id'] == $userModel::TYPE_MANAGE || $user['group_id'] == $userModel::TYPE_JUDGES){
            $where['mp.match_id'] = array('GT',0);
            $porjectModel_l->where($where);
        }else {
            $where['t.user_id'] = array('eq',$user['user_id']);
            $porjectModel_l->where($where);
        }
        $result_data = $porjectModel_l->limit($Page->firstRow.','.$Page->listRows)
        ->select();
        
        $list_data = array();
        if (!empty($result_data)){
            foreach ($result_data as $k => $v){
                if ($v['creat_id'] == $user['user_id']){
                    $v['is_captain'] = 1;
                    $list_data[$v['id']] = $v;
                }else {
                    $v['is_captain'] = 0;
                    $list_data[$v['id']] = $v;
                }
            }
        }
        return array('Page' => $page_show , 'list_data' => $list_data);
    }
    
	/**
	 * 获取项目列表
	 * 添加时间 2016-03-17
	 * @author zlj
	 */
    public function lists($user){
    	$porjectModel = new \Common\Model\ProjectModel();
		$userModel = new \Common\Model\UsersModel();
		$teamModel = new \Common\Model\TeamModel();
		$where = array();
		
		if($user["user_type"] == $userModel::TYPE_STUDENT){
			//学生
			$where["t.user_id"] = $user["user_id"];
		}elseif($user["user_type"] == $userModel::TYPE_TEACHER){
			//指导老师
			$where["tt.user_id"] = $user["user_id"];
			$where["tt.teacher_type"] = 1;
		}elseif($user["user_type"] == $userModel::TYPE_JUDGES){
			//评审专家
			$where["j.judge_id"] = $user["user_id"];
		}elseif($user["user_type"] == $userModel::TYPE_MANAGE){
			//管理员
			$where["t.user_type"] = \Common\Model\TeamModel::USER_TYPE_CAPTAIN;
		}elseif($user["user_type"]==$userModel::TYPE_INVESTMENT){
			//投资人
			$where["p.is_open"] = 1;
		}

		$where["t.id"] = array("neq","");

		$field = "p.name,p.is_open,p.creat_id,p.sub_title,t.user_type,t.img_url,p.id as pid,ps.score,ps.result";
        $teamModel = $teamModel->alias('t')
           ->join('project AS p ON (p.team_id = t.leader_id)')
		   ->join('match_project AS mp ON (p.id = mp.project_id)','left')
           ->join('`match` AS m ON(m.id = mp.match_id)','left')
		   ->join('project_status AS ps ON(p.id = ps.project_id)','left');
		
		if($user["user_type"] == $userModel::TYPE_TEACHER){
			$teamModel = $teamModel->join('teacher_team AS tt ON (tt.project_id=p.id)','left');
		}elseif($user["user_type"] == $userModel::TYPE_JUDGES){
			$teamModel = $teamModel->join('judges AS j ON (j.project_id=m.id)','left');
		}
		
		$count =  $teamModel->field($field)->where($where)->group("t.leader_id")->count();

        $Page = new \Think\Page($count,12);
		$show = $Page->show();

        $teamModel = $teamModel->alias('t')
           ->join('project AS p ON (p.team_id = t.leader_id)')
		   ->join('match_project AS mp ON (p.id = mp.project_id)','left')
           ->join('`match` AS m ON(m.id = mp.match_id)','left')
		   ->join('project_status AS ps ON(p.id = ps.project_id)','left');
		
		if($user["user_type"] == $userModel::TYPE_TEACHER){
			$teamModel = $teamModel->join('teacher_team AS tt ON (tt.project_id=p.id)','left');
		}elseif($user["user_type"] == $userModel::TYPE_JUDGES){
			$teamModel = $teamModel->join('judges AS j ON (j.project_id=m.id)','left');
		}
		
		$res = $teamModel->field($field)->where($where)->group("t.leader_id")->order("p.create_time desc")->limit($Page->firstRow.','.$Page->listRows)->select();

        if (!empty($res)){
            foreach ($res as $k => $v){
            	if($user["user_type"] == $userModel::TYPE_MANAGE){
            		$res[$k]['is_captain'] = 1;
            	}else{
            		if ($v['creat_id'] == $user['user_id']){
	                    $res[$k]['is_captain'] = 1;
	                }else {
	                    $res[$k]['is_captain'] = 0;
	                }
            	}
            }
        }
		return array('Page' => $show , 'list_data' => $res);
    }

    /**
     * 获取一条项目数据
     * 添加时间15:07:10
     * 
     * @author yzx
     * @param int $id
     * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, object>
     */
    public function getData($id) {
        $porjectModel = new \Common\Model\ProjectModel();
        $result = $porjectModel->where(array('id' => $id))->find();
        return $result;
    }
}