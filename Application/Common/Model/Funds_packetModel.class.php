<?php
namespace Common\Model;
class Funds_packetModel extends \Common\Helper\Model{
	
    protected $tableName = 'funds_packet';
    /**
     * 添加比赛组名称
     * 添加时间2016-2-26
     * 
     * @author zlj
     * @param array $name 比赛组名称
	 * @param string $projectid 比赛id
     * @return bool
     */
    public function addName($name,$projectid) {
        
		$flag = FALSE;
		foreach($name as $_k){
			$res = $this->add(array("project_id"=>$projectid,"class_name"=>$_k));
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