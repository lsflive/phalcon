<?php

namespace app\modules\admin\controller;

/**
* 后台：系统
*/
class SystemController extends UserBase{
	/* 首页 */
	public function indexAction(){
		// 获取菜单
		$menus = $this->getMenus();
		$this->view->setVar('Menus',$menus);
		// 视图
		$this->view->setTemplateAfter('main');
		$this->view->pick("system/index");
	}
}