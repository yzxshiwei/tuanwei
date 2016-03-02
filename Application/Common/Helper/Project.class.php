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
        ->join('match_project AS mp ON (p.id = mp.project_id)')
        ->join('`match` AS m ON(m.id = mp.match_id)')
        ->join('project_status AS ps ON(p.id = ps.project_id)')
        ->join('team as t ON(t.project_id = p.id)');
        if ($user['group_id'] == $userModel::TYPE_MANAGE){
            $where['mp.match_id'] = array('GT',0); 
            $porjectModel->where($where);
        }else {
            $where['t.user_id'] = array('eq',$user['user_id']);
            $porjectModel->where($where);
        }
        $count =  $porjectModel->count();
        $Page = new \Think\Page($count,12);
        $page_show = $Page->show();
        $porjectModel_l
        ->field('p.id,p.creat_id,p.intro,p.name,p.sub_title,m.name AS m_name,m.id AS m_id,ps.score,ps.result,t.user_type')
        ->alias('p')
        ->join('match_project AS mp ON (p.id = mp.project_id)')
        ->join('`match` AS m ON(m.id = mp.match_id)')
        ->join('project_status AS ps ON(p.id = ps.project_id)')
        ->join('team as t ON(t.project_id = p.id)');
        if ($user['group_id'] == $userModel::TYPE_MANAGE){
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