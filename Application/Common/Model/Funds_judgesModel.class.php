<?php
namespace Common\Model;
class Funds_judgesModel extends \Common\Helper\Model{
	
    protected $tableName = 'funds_judges';
    const STATE_ADOPT = 1;
    const STATE_NOE_ADOPT = 0;
    /**
     * 状态
     * @var unknown
     */
    public $state = array(
        self::STATE_NOE_ADOPT => '未通过',
        self::STATE_ADOPT => '通过',
    );
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