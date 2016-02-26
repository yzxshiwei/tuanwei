<?php
namespace Common\Model;
class JudgesModel extends \Common\Helper\Model{
	
    protected $tableName = 'Judges';
    /**
     * 添加比赛评委
     * 添加时间2016-2-26
     * 
     * @author zlj
     * @param array $teacherid 评委id
	 * @param string $projectid 比赛id
     * @return bool
     */
    public function addJudges($teacherid,$projectid) {
        
		$flag = FALSE;
		foreach($teacherid as $_k){
			$res = $this->add(array("project_id"=>$projectid,"judge_id"=>$_k));
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