<?php
namespace Admin\Controller;
/**
 * 后台控制器
 * 添加时间10:53:18
 * 
 * @author yzx
 *
 */
class IndexController extends \Admin\Controller\Controller {
    /**
     * 登录消息提示页面
     * 添加时间2016-2-17
     * 
     * @author yzx
     */
    public function index(){
        $this->display();
    }
    /**
     * 人员管理
     * 添加时间2016-2-17
     * 
     * @author yzx
     */
    public function usermanage() {
        $this->display();
    }
	/**
	 * 团队管理
	 * 添加时间11:01:20
	 * 
	 * @author yzx
	 */
	public function teammanage() {
		$this->display();
	}
	/**
	 * 个人信息
	 * 添加时间15:27:05
	 * 
	 * @author yzx
	 */
	public function person() {
	    $this->display();
	}
}