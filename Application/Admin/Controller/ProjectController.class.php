<?php
namespace Admin\Controller;
class ProjectController extends Controller{
    /**
     * 项目管理
     * 添加时间14:53:48
     *
     * @author yzx
     */
    public function projectmanage() {
        $project = new \Common\Helper\Project();
        $result = $project->listData();
        $this->assign('Page' , $result['Page']);
        $this->assign('list_data',$result['list_data']);
        $this->display();
    }
    /**
     * 作品评审
     * 添加时间15:50:59
     * 
     * @author yzx
     */
    public function workreview() {
        $this->display();
    }
    /**
     * 创建项目
     * 添加时间15:34:58
     * 
     * @author yzx
     */
    public function createproject() {
        if (IS_POST){
           $file_res = uploadFile('project_file');
           if (!$file_res['status']){
               $this->error($file_res['msg']);
           }else {
               $posjectModel = new \Common\Model\ProjectModel();
               $post_data = array();
               $post_data['name'] = I('post.name','','string');
               $post_data['sub_title'] = I('post.sub_title','','string');
               $post_data['file_url'] = $file_res['file_path'];
               $post_data['intro'] = I('post.intro','','string');
               
               $result = $posjectModel->addPorject($post_data);
               if ($result){
                   $this->success('创建项目成功',U('Project/projectmanage'));
               }else {
                   $this->error('创建项目失败');
               }
           }
        }else {
            $this->display();
        }
    }
}