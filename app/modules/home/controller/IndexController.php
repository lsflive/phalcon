<?php

namespace app\modules\home\controller;

/**
* 网站：首页
*/
class IndexController extends ControllerBase{

	public function indexAction(){
		// 视图
		$this->view->setTemplateAfter('main');
		return $this->view->pick('index/index');
	}

}

