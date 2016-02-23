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
            $file_res = uploadFile('selectFiles','img');
            if (!$file_res['status']){
                $this->error($file_res['msg']);
            }
            $input_data = array();
            $input_data['col'] = I('post.col',false,'strval'); //新闻栏目
            $input_data['sub_col'] = I('post.sub_col', false, 'strval'); //新闻栏目子类别
            $input_data['top_s'] = I('post.top_s', '', 'strtotime'); 
            $input_data['top_e'] = I('post.top_e', '', 'strtotime');
            $input_data['title'] = I('post.title', false, 'strval');
            $input_data['subtitle'] = I('post.subtitle', '', 'strval');
            $input_data['type'] = I('post.type', false, 'strval');
            $input_data['source'] = I('post.source', '', 'strval'); //新闻消息来源
            $input_data['content'] = I('post.editorValue', false, 'strval'); //新闻编辑内容
            $input_data['public_t'] = time();
            $input_data['img_url'] = $file_res['file_path'];
            
            $News = M('news');
            $flag = $News->add($input_data);
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