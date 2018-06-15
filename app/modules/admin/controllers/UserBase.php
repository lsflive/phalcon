<?php

namespace Phalcon\Modules\Admin\Controllers;

use Phalcon\Modules\Admin\Models\SysAdmin;
use Phalcon\Modules\Admin\Models\SysMenus;
use Phalcon\Modules\Admin\Models\SysMenuAction;

/**
* 后台：首页
*/
class UserBase extends ControllerBase{

	static private $perm = '';
	static private $cid=[];

	/* 构造函数 */
	public function initialize(){
		// 获取网址
		$this->view->setVar('base_url',$this->url->get($this->dispatcher->getModuleName().'/'));
		$this->view->setVar('getUrl','');
		// 是否登录
		$admin = $this->session->get('Admin');
		if(!$admin || !$admin['login'] || $admin['ltime']<time()){
			return $this->redirect('index/logout');
		}else{
			$_SESSION['Admin']['ltime'] = time()+1800;
		}
		// 菜单权限
		$perm = SysAdmin::findFirst(array('id='.$admin['id'],'columns'=>'perm'));
		$data = [];
		$arr = explode(' ',$perm->perm);
		foreach($arr as $val){
			$a = explode(':',$val);
			$data[$a[0]] = $a[1];
		}
		// 判断权限
		$mid = SysMenus::findFirst(array('url="'.$this->dispatcher->getControllerName().'"','columns'=>'id'));
		if(!isset($data[$mid->id])){
			return $this->redirect('index/logout');
		}
		// 赋值权限
		self::$perm = $data;
		// 用户信息
		$this->view->setVar('Uinfo',$admin);
	}

	/* 获取菜单 */
	function getMenus(){
		// CID
		$C = SysMenus::findFirst(['url="'.$this->dispatcher->getControllerName().'"','columns'=>'id,fid,title']);
		self::$cid[] = $C->id;
		$fids = self::getCid($C->fid);
		krsort(self::$cid);
		self::$cid = array_values(self::$cid);
		// 数据
		return [
			'Ctitle'=>$C->title,
			'CID'=>self::$cid,
			// 获取菜单动作
			'action'=>self::actionMenus(self::$perm[end(self::$cid)]),
			'Data'=>self::getMenu()
		];
	}
	// 递归菜单
	static private function getMenu($fid=0){
		$data=[];
		$M = SysMenus::find(['fid='.$fid,'columns'=>'id,fid,title,url,ico']);
		foreach($M as $val){
			if(isset(self::$perm[$val->id])){
				$val->menus = self::getMenu($val->id);
				$data[] = $val;
			}
		}
		return $data;
	}
	// 动作菜单
	static private function actionMenus($perm=''){
		$data = array();
		// 全部动作菜单
		$aMenus = SysMenuAction::find(['columns'=>'name,ico,perm']);
		foreach($aMenus as $val){
			// 匹配权限值
			if(intval($perm)&intval($val->perm)){
				$data[] = array('name'=>$val->name,'ico'=>$val->ico);
			}
		}
		return $data;
	}
	// 递归CID
	static private function getCid($fid){
		if($fid!=0){
			$m = SysMenus::findFirst(['id='.$fid,'columns'=>'id,fid']);
			self::$cid[] = $m->id;
			self::getCid($m->fid);
		}
	}
}