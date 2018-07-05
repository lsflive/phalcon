<?php

namespace app\modules\admin\controller;

/**
* 后台：首页
*/
class WelcomeController extends UserBase{
	/* 首页 */
	public function indexAction(){
		// 跳转用户首页
		$this->redirect('Desktop');
	}
}