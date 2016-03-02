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
        return $this->where(array('project_id' => $one_tema['project_id']))->delete();
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
	   	   $adduser = $this->data(array("user_id"=>$_k,"project_id"=>$projectid,"user_type"=>\Common\Model\TeamModel::USER_TYPE_MEMBER))->add();
		   if($adduser){
		   	   $flag = TRUE;
		   }else{
		   	   $flag = FALSE;
			   break;
		   }
	    }
		
		//添加队长
		if($type){   
	        $res = $this->data(array("user_id"=>$memberid,"project_id"=>$projectid,"user_type"=>\Common\Model\TeamModel::USER_TYPE_CAPTAIN))->add();
		}

	    if($flag && $res){
	    	   return True;
	    }else{
	    	   return FALSE;
	    }
	}


	 
	 
	 
	 
	 
	 
	 
	 
}