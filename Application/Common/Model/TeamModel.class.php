<?php
namespace Common\Model;
class TeamModel extends \Common\Helper\Model{
    protected $tableName = 'team';
    /**
     * 用户类型
     * 添加时间2016-2-24
     * 
     * @author yzx
     * @var unknown
     */
    const USER_TYPE_MEMBER = 'member';
    const USER_TYPE_CAPTAIN = 'captain';
    /**
     * 添加时间2016-2-24
     * 
     * @author yzx
     * @var array
     */
    public $user_type = array(self::USER_TYPE_MEMBER => '队员',
                              self::USER_TYPE_CAPTAIN => '队长');
    /**
     * 获取团队列表
     * 添加时间2016-2-24
     * 
     * @author yzx
     * @param unknown $user_id
     * @return array
     */
    public function listData($user_id) {
        $result = $this->field('p.name,team.id,u.user_name,u.create_time')
             ->join('project as p on (team.project_id = p.id)','left')
             ->join("users as u on (team.user_id = u.user_id)",'left')
             ->where(array('user_id'=>$user_id,'user_type' => self::USER_TYPE_CAPTAIN))
             ->select();
        return $result;
    }
	
	/**
	 * 添加项目时添加团队
	 * 添加时间2016-2-24
	 * @param projectid  int    项目id
	 * @param userid     array  项目成员id
	 * @param memberid   int    项目队长id
	 * @author zlj
	 * @return bool
	 */
	public function addTeam($projectid,$userid,$memberid){

        $flag = FALSE;
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
	   $res = $this->data(array("user_id"=>$memberid,"project_id"=>$projectid,"user_type"=>\Common\Model\TeamModel::USER_TYPE_CAPTAIN))->add();
	   if($flag && $res){
	   	   return True;
	   }else{
	   	   return FALSE;
	   }
	}
	
}