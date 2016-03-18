<?php
namespace Common\Model;
class TeamModel extends \Common\Helper\Model{
    protected $tableName = 'team';
    /**
     * 用户类型
     * @author yzx
     * @var 队员
     */
    const USER_TYPE_MEMBER = 'member';
    /**
     * 用户类型
     * @author yzx
     * @var 队长
     */
    const USER_TYPE_CAPTAIN = 'captain';
    
    const STATE_INVITE = 0;
    const STATE_PASS = 1;
    const STATE_NOT_PASS = 2;
    /**
     * 邀请状态
     * @var unknown
     */
    public $state = array(
        self::STATE_INVITE => '邀请中',
        self::STATE_PASS => '同意',
        self::STATE_NOT_PASS => '拒绝'
    );
    /**
     * 添加时间2016-2-24
     * 
     * @author yzx
     * @var array
     */
    public $user_type = array(self::USER_TYPE_MEMBER => '队员',
                              self::USER_TYPE_CAPTAIN => '队长');
    /**
     * 删除团队
     * 添加时间2016-2-24
     * 
     * @author yzx
     * @param int $team_id
     * @return bool
     */
    public function deleteTeam($team_id){
        $one_tema = $this->where(array('id' => $team_id))->find();
        if (!is_array($one_tema) || empty($one_tema)) {
            return false;
        }
        return $this->where(array('leader_id' => $one_tema['project_id']))->delete();
    }
	
	/**
	 * 添加项目时添加团队
	 * 添加时间2016-2-24
	 * @param projectid  int    项目id
	 * @param userid     array  项目成员id
	 * @param memberid   int    项目队长id
	 * @param type       bool   ture 添加队长   false不添加
	 * @author zlj
	 * @return bool
	 */
	public function addTeam($projectid,$userid,$memberid,$type){

        $flag = FALSE;
		$res = true;
		foreach($userid as $_k){
		   
		   $reluse = $this->where(array("user_id"=>$_k,"project_id"=>$projectid))->find();

		   if(!$reluse){
			   $adduser = $this->data(array("user_id"=>$_k,"project_id"=>$projectid,"state"=>\Common\Model\TeamModel::STATE_INVITE,"user_type"=>\Common\Model\TeamModel::USER_TYPE_MEMBER))->add();
			   if($adduser){
			   	   	
			   	   $messageModel = new \Common\Model\MessageModel();
				   $messageModel->sendMsg($_k,$memberid,$messageModel::TYPE_USER_PROJECT, '你有项目邀请参加',$projectid);
			   	   $flag = TRUE;
			   }else{
			   	   $flag = FALSE;
				   break;
			   }
		   }
	    }
		
		//添加队长
		if($type){   
	        $res = $this->data(array("user_id"=>$memberid,"project_id"=>$projectid,"state"=>\Common\Model\TeamModel::STATE_PASS,"user_type"=>\Common\Model\TeamModel::USER_TYPE_CAPTAIN))->add();
		}

	    if($flag && $res){
	    	   return True;
	    }else{
	    	   return FALSE;
	    }
	}

	/**
	 * 添加团队
	 * 添加时间2016-3-17
	 * @param member_name  string    队长名
	 * @param userid     array  项目成员user表 id 
	 * @param memberid   int    项目队长team表 id
	 * @author zlj
	 * @return bool
	 */
	public function teamAdd($userid,$memberid,$member_name){
		
		$messageModel = new \Common\Model\MessageModel();
		$flag = FALSE;
		foreach($userid as $_k){
		   	
		    $reluse = $this->where(array("user_id"=>$_k,"leader_id"=>$memberid))->find();
		    if(!$reluse){
		   	    $data = array();
			    $data["user_id"] = $_k;
			    $data["leader_id"] = $memberid;
	            $data["state"] = \Common\Model\TeamModel::STATE_INVITE;
			    $data["user_type"] = \Common\Model\TeamModel::USER_TYPE_MEMBER;
	
			    $adduser = $this->data($data)->add();
			    if($adduser){

				    $messageModel->sendMsg($_k,$memberid,$messageModel::TYPE_USER_PROJECT, $member_name.'邀请您参加他的团队',$memberid);
			        $flag = TRUE;
	   		    }else{
			   	    $flag = FALSE;
				    break;
			    }
		    }
	    }
		if($flag){
	    	return TRUE;
	    }else{
	    	return FALSE;
	    }
	}
	 
	 
	 
	 
	 
	 
	 
	 
}