<?php
namespace Common\Model;
class Teacher_TeamModel extends \Common\Helper\Model{
    protected $tableName = 'teacher_team';
	
	/**
	 * 添加项目的老师团队
	 * 添加时间 2016-2-25
	 * 
	 * @param userid array 学生(老师)公用id
	 * @param pid string   项目id
	 * @param type string  1:指导  2评审
	 * @author zlj
	 * @return bool
	 */
	public function addTeam($userid,$pid,$type){
		
		$flag = FALSE;
		foreach($userid as $_v){
			$add = array(
			    "user_id" => $_v,
			    "project_id" => $pid,
			    "teacher_type" => $type
			);
			$res = $this->add($add);
			if($res){
		   	   $flag = TRUE;
		    }else{
		   	   $flag = FALSE;
			   break;
		    }
		}
		
		return $flag;
	}
	
}