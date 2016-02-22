<?php
namespace Admin\Controller;
/**
 * 后台新闻管理控制器
 * 添加时间13:06:51
 * 
 * @author yzx
 *
 */
class NewsController extends Controller{
    /**
     * 创建新闻
     * 添加时间13:07:23
     * 
     * @author yzx
     */
    public function createnews() {
        if(IS_POST){
            $role = I('post.role',false,'strval'); //新闻栏目
            $sub_role = I('post.sub_role', false, 'strval'); //新闻栏目子类别
            $race_start_date = I('post.race-start-date', '', 'strtotime'); 
            $race_end_date = I('post.race_end_date', '', 'strtotime');
            $input_title_name = I('post.input_title_name', false, 'strval');
            $input_subtitle_name = I('post.input_subtitle_name', '', 'strval');
            $news_type = I('post.news_type', false, 'strval');
            $input_from_name = I('post.input_from_name', '', 'strval'); //新闻消息来源
            $editorValue = I('post.editorValue', false, 'strval'); //新闻编辑内容
            
            if($role && $sub_role && $input_title_name && $news_type && $editorValue){
                $this->error('填写信息不完整');
            }
            
            switch ($role) {
                case '1':
                    $role = '新闻主页';
                    ;
                    break;
                case '2':
                    $role = '创业政策';
                    ;
                    break;
                case '3':
                    $role = '讲座培训';
                    ;
                    break;
                case '4':
                    $role = '学院风采';
                    ;
                    break;
                case '5':
                    $role = '资料库';
                    ;
                    break;
            }
            
            switch ($sub_role) {
                case '0':
                    $sub_role = '';
                    ;
                    break;
                case '1':
                    $sub_role = '学校';
                    ;
                    break;
                case '2':
                    $sub_role = '地方';
                    ;
                    break;
                case '3':
                    $sub_role = '国家';
                    ;
                    break;
            }
            
            switch ($news_type) {
                case '1':
                    $news_type = '轮播图';
                    ;
                    break;
                case '2':
                    $news_type = '普通新闻';
                    ;
                    break;
                case '3':
                    $news_type = '图片新闻';
                    ;
                    break;
            }
            
            $News = M('news');
            $flag = $News->add(array(
                'title' => $input_title_name,
                'subtitle' => $input_subtitle_name,
                'col' => $role,
                'sub_col' => $sub_role,
                'flag' => 0,
                'public_t' => time(),
                'top_s' => $race_start_date,
                'top_e' => $race_end_date,
                'content' => $editorValue
            ));
            if(!$flag){
                $this->error('发布失败');
            }
            else {
                $this->success('发布成功');
            }
            
        }
        $this->display();
    }
    /**
     * 信息管理
     * 添加时间14:39:51
     * 
     * @author yzx
     */
    public function newsmanage() {
        $this->display();
    }
} 