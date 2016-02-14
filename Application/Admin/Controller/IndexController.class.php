<?php
namespace Admin\Controller;

class IndexController extends \Admin\Controller\Controller {
    public function index(){
        var_dump(\Common\Helper\RunUser::newInstantiation()->getInfo());
    }
}