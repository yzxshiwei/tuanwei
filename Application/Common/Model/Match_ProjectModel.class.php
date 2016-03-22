<?php
namespace Common\Model;
class Match_ProjectModel extends \Common\Helper\Model{
    protected $tableName = 'match_project';
    /**
     * 获取比赛报名项目列表
     * 添加时间2016-3-1
     * 
     * @author yzx
     * @param int $matchId
     * @return Ambigous <mixed, boolean, string, NULL, multitype:, unknown, object>
     */
    public function listData($matchId, $order) {
        $result = $this->field("p.name,p.id,match_project.match_id,ps.score")
        ->join("project as p on (p.id = match_project.project_id)","left")
        ->join('project_status as ps on(ps.project_id = p.id)','left')
        ->where(array('match_id' => $matchId))
        ->order($order)
        ->select();
        return $result;
    }
}