<?php

namespace app\modules\admin\controller;

use app\modules\admin\model\SysMenus;
use app\modules\admin\model\SysMenuAction;

/**
* 后台：菜单管理
*/
class SysMenusController extends UserBase{
	/* 首页 */
	public function indexAction(){
		// Page
		if(isset($_GET['search'])){
			$like = $this->pageWhere();
			$where = '';
			foreach ($like['data'] as $key => $val){
				$where .= $key." LIKE '%".$val."%' AND ";
			}
			$where = rtrim($where,'AND ');
			$data = SysMenus::find([$where,'order'=>'fid desc,sort desc,id desc']);
			$getUrl = $like['getUrl'];
		}else{
			$getUrl = '';
			$data = SysMenus::find(['order'=>'fid desc,sort desc,id desc']);
		}
		$this->view->setVar('Page',$this->getPage([
			'data'=>$data,
			'getUrl'=>$getUrl
		]));
		// 获取菜单
		$this->view->setVar('Menus',$this->getMenus());
		// JS
		$this->view->setVar('LoadJS', array('system/sys_menus.js'));
		// 视图
		$this->view->setTemplateAfter('main');
		return $this->view->pick("system/menus/index");
	}

	/* 搜索 */
	function searchAction(){
		return $this->view->pick('system/menus/sea');
	}

	/* 添加 */
	function addAction(){
		// 所有权限
		$perm = SysMenuAction::find(array('columns'=>'name,perm'));
		$this->view->setVar('perm',$perm);

		return $this->view->pick('system/menus/add');
	}
	function addDataAction(){
		// 是否有数据提交
		if($this->request->isPost()){
			// 采集数据
			$data = [
				'fid'=>trim($this->request->getPost('fid')),
				'title'=>trim($this->request->getPost('title')),
				'url'=>trim($this->request->getPost('url')),
				'perm'=>trim($this->request->getPost('perm')),
				'ico'=>trim($this->request->getPost('ico')),
				'sort'=>trim($this->request->getPost('sort')),
				'remark'=>trim($this->request->getPost('remark'))
			];
			// 实例化
			$model = new SysMenus();
			// 执行添加
			if($model->save($data)===false){
				return $this->response->setJsonContent(['state'=>'n','msg'=>'添加失败！']);
			}else{
				return $this->response->setJsonContent(['state'=>'y','url'=>'SysMenus','msg'=>'添加成功！']);
			}
		}
	}

	/* 编辑 */
	function editAction(){
		// 数据
		$edit = SysMenus::findFirst('id='.$this->request->getPost('id'));
		// 所有权限
		$perm = SysMenuAction::find(['columns'=>'name,perm']);
		$this->view->setVar('perm',$perm);
		// 视图
		$this->view->setVar('edit',$edit);
		return $this->view->pick('system/menus/edit');
	}
	function editDataAction(){
		// 是否有数据提交
		if($this->request->isPost()){
			// 采集数据
			$data = [
				'fid'=>trim($this->request->getPost('fid')),
				'title'=>trim($this->request->getPost('title')),
				'url'=>trim($this->request->getPost('url')),
				'perm'=>trim($this->request->getPost('perm')),
				'ico'=>trim($this->request->getPost('ico')),
				'sort'=>trim($this->request->getPost('sort')),
				'remark'=>trim($this->request->getPost('remark'))
			];
			// 实例化
			$model = SysMenus::findFirst([
				'id=:id:',
				'bind'=>['id'=>$this->request->getPost('id')]
			]);
			// 返回信息
			if($model->save($data)===false){
				return $this->response->setJsonContent(['state'=>'n','msg'=>'编辑失败！']);
			}else{
				return $this->response->setJsonContent(['state'=>'y','url'=>'SysMenus','msg'=>'编辑成功！']);
			}
		}
	}

	/* 删除 */
	function delAction(){
		return $this->view->pick('system/menus/del');
	}
	function delDataAction(){
		// 是否有数据提交
		if($this->request->isPost()){
			$id = implode(',',json_decode($this->request->getPost('id')));
			// 数据
			$model = SysMenus::find('id IN('.$id.')');
			if($model->delete()===false){
				return $this->response->setJsonContent(['state'=>'n','msg'=>'删除失败！']);
			}else{
				return $this->response->setJsonContent(['state'=>'y','url'=>'SysMenus','msg'=>'删除成功！']);
			}
		}
	}

	/* 联动菜单数据 */
	function getMenuAction(){
		$fid = $this->request->getPost('fid');
		// 实例化
		$mData = SysMenus::find(['fid='.$fid,'columns'=>'id,title']);
		// 数据
		$data = [];
		foreach($mData as $val){
			$data[] = [$val->id,$val->title];
		}
		// 返回数据
		return $this->response->setJsonContent($data);
	}
}