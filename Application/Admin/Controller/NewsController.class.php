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
     * @author MuTao
     */
    public function createnews() {
        if(IS_POST){
			$input_data = array();
			
			if($_FILES['selectFiles']['tmp_name']){
				$file_res = Upload($_FILES['selectFiles']);
				if($file_res["status"]){
					$input_data['img_url'] = $file_res['file_path'];
				}else{
					$this->error($file_res['msg']);
				}
			}
			
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
        $news = new \Common\Helper\News();
        $result = $news->listData();

        foreach($result["list_data"] as $_k=>$_v){
        	switch ($_v["col"])
			{
			case \Common\Model\NewsModel::COL_1:
              $result["list_data"][$_k]["col"] = "新闻主页";
			  break;  
			case \Common\Model\NewsModel::COL_2:
              $result["list_data"][$_k]["col"] = "创业政策";
			  break;
			case \Common\Model\NewsModel::COL_3:
              $result["list_data"][$_k]["col"] = "讲座培训";
			  break; 
            case \Common\Model\NewsModel::COL_4:
              $result["list_data"][$_k]["col"] = "创业动态";
			  break;
			case \Common\Model\NewsModel::COL_5:
              $result["list_data"][$_k]["col"] = "资料库";
			  break;
			case \Common\Model\NewsModel::COL_6:
              $result["list_data"][$_k]["col"] = "场地类别";
			  break;
			}
        }
        $this->assign('Page' , $result['Page']);
        $this->assign('list_data',$result['list_data']);
        $this->display();

    }
	
	/**
	 * 新闻编辑
	 * @author zlj
	 */
	public function newedit(){
		$newsModel = new \Common\Model\NewsModel();
		if(IS_POST){
			$nid = I("post.nid","","string");
			$input_data = array();
			
			if($_FILES['selectFiles']['tmp_name']){
				$file_res1 = Upload($_FILES['selectFiles']);
				if($file_res1["status"]){
					
					$input_data["img_url"] = $file_res1['file_path'];
					$file_url = $newsModel->where(array("id"=>$nid))->field("img_url")->find();
					delfile($file_url["img_url"]);
				}else{
					$this->error($file_res1['msg']);
				}
			}
            $input_data['col'] = I('post.col',false,'strval'); //新闻栏目
            $input_data['sub_col'] = $input_data['col']==2?I('post.sub_col', false, 'strval'):0; //新闻栏目子类别
            $input_data['top_s'] = I('post.top_s', '', 'strtotime'); 
            $input_data['top_e'] = I('post.top_e', '', 'strtotime');
            $input_data['title'] = I('post.title', false, 'strval');
            $input_data['subtitle'] = I('post.subtitle', '', 'strval');
            $input_data['type'] = I('post.type', false, 'strval');
            $input_data['source'] = I('post.source', '', 'strval'); //新闻消息来源
            $input_data['content'] = I('post.editorValue', false, 'strval'); //新闻编辑内容
            
            $relust = $newsModel->where(array("id"=>$nid))->save($input_data);

			if($relust){
				$this->success("更新成功",U('News/newsmanage'));
			}else{
				$this->error("更新失败");
			}
			
		}else{
			$nid = I("get.nid","","string");
			$info = $newsModel->where(array("id"=>$nid))->find();
			$this->assign("info",$info);
			$this->display();
		}
	}
	
	/**
	 * 修改新闻状态
	 * 添加时间 2016-2-23
	 * 
	 * @author zlj
	 */
	 public function new_flag(){
	 	 if(IS_AJAX){
	 	 	$nid = I("post.nid","","string");
			 
			// $type_new    1:允许通过 2：拒绝通过
			$type_new = I("post.type_new","","string");
			
			$news = M("news");
			$return = $news->where(array("id"=>$nid))->save(array("flag"=>$type_new));
            
			if($return){
				echo 1;
			}else{
				echo 2;
			}
	 	 }
	 }
	 
	 /**
	  * 新闻删除
	  * 添加时间2016-03-08
	  * 
	  * @author zlj
	  */
	 public function delNew(){
	 	if(IS_AJAX){
	 		$nid = I("post.nid","","string");
			$news = M("news");
			$img_url = $news->where(array("id"=>$nid))->field("img_url")->find();
			$res = $news->where(array("id"=>$nid))->delete();
			if($res){
				delfile($img_url["img_url"]);
				echo 1;
			}else{
				echo 2;
			}
	 	}
	 }
	 
	 
} 