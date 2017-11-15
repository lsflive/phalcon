<?php

namespace Phalcon\Modules\Admin\Controllers;

/**
* 后台：首页
*/
class DesktopController extends UserBase{
	/* 首页 */
	public function indexAction(){
		// 获取菜单
		$menus = $this->getMenus();
		$this->view->setVar('Menus',$menus);
		// 视图
		$this->view->setTemplateAfter('main');
		$this->view->pick("desktop/index");
	}
}