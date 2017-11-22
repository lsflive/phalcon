<?php

namespace Phalcon\Modules\Admin\Controllers;

use Phalcon\Modules\Admin\Models\SysMenuAction;

/**
* 后台：菜单动作
*/
class SysMenusActionController extends UserBase{
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
			$data = SysMenuAction::find([$where,'order'=>'id desc']);
			$getUrl = $like['getUrl'];
		}else{
			$getUrl = '';
			$data = SysMenuAction::find(['order'=>'id desc']);
		}
		$this->view->setVar('Page',$this->getPage([
			'data'=>$data,
			'getUrl'=>$getUrl
		]));
		// 获取菜单
		$this->view->setVar('Menus',$this->getMenus());
		// JS
		$this->view->setVar('LoadJS', array('system/sys_menus_action.js'));
		// 视图
		$this->view->setTemplateAfter('main');
		return $this->view->pick("system/action/index");
	}

	/* 搜索 */
	function searchAction(){
		return $this->view->pick('system/action/sea');
	}

	/* 添加 */
	function addAction(){
		return $this->view->pick('system/action/add');
	}
	function addDataAction(){
		// 是否有数据提交
		if($this->request->isPost()){
			// 采集数据
			$data = [
				'name'=>trim($this->request->getPost('name')),
				'perm'=>trim($this->request->getPost('perm')),
				'ico'=>trim($this->request->getPost('ico'))
			];
			// 实例化
			$model = new SysMenuAction();
			// 执行添加
			if($model->save($data)===false){
				return $this->response->setJsonContent(['state'=>'n','msg'=>'添加失败！']);
			}else{
				return $this->response->setJsonContent(['state'=>'y','url'=>'SysMenusAction','msg'=>'添加成功！']);
			}
		}
	}

	/* 编辑 */
	function editAction(){
		// 数据
		$edit = SysMenuAction::findFirst('id='.$this->request->getPost('id'));
		// 视图
		$this->view->setVar('edit',$edit);
		return $this->view->pick('system/action/edit');
	}
	function editDataAction(){
		// 是否有数据提交
		if($this->request->isPost()){
			// 采集数据
			$data = [
				'name'=>trim($this->request->getPost('name')),
				'perm'=>trim($this->request->getPost('perm')),
				'ico'=>trim($this->request->getPost('ico'))
			];
			// 实例化
			$model = SysMenuAction::findFirst([
				'id=:id:',
				'bind'=>['id'=>$this->request->getPost('id')]
			]);
			// 返回信息
			if($model->save($data)===false){
				return $this->response->setJsonContent(['state'=>'n','msg'=>'编辑失败！']);
			}else{
				return $this->response->setJsonContent(['state'=>'y','url'=>'SysMenusAction','msg'=>'编辑成功！']);
			}
		}
	}
	/* 删除 */
	function delAction(){
		return $this->view->pick('system/action/del');
	}
	function delDataAction(){
		// 是否有数据提交
		if($this->request->isPost()){
			$id = implode(',',json_decode($this->request->getPost('id')));
			// 数据
			$model = SysMenuAction::find('id IN('.$id.')');
			if($model->delete()===false){
				return $this->response->setJsonContent(['state'=>'n','msg'=>'删除失败！']);
			}else{
				return $this->response->setJsonContent(['state'=>'y','url'=>'SysMenusAction','msg'=>'删除成功！']);
			}
		}
	}
}