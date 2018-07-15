<?php

namespace app\modules\admin\controller;

use app\library\Page;
use app\library\Safety;

use app\modules\admin\model\SysAdmin;
use app\modules\admin\model\SysMenus;
use app\modules\admin\model\SysMenuAction;

/**
* 后台：系统用户
*/
class SysAdminsController extends UserBase{
	/* 首页 */
	function indexAction(){
		// Page
		if(isset($_GET['search'])){
			$like = Page::where();
			$where = '';
			foreach ($like['data'] as $key => $val){
				$where .= $key." LIKE '%".$val."%' AND ";
			}
			$where = rtrim($where,'AND ');
			$data = SysAdmin::find([$where,'order'=>'id desc']);
			$getUrl = $like['getUrl'];
			$this->view->setVar('getUrl',$like['search']);
		}else{
			$getUrl = '';
			$data = SysAdmin::find(['order'=>'id desc']);
		}
		$this->view->setVar('Page',Page::get([
			'controller'=>$this->dispatcher->getModuleName().'/'.$this->dispatcher->getControllerName(),
			'data'=>$data,
			'getUrl'=>$getUrl
		]));
		// 获取菜单
		$this->view->setVar('Menus',$this->getMenus());
		// JS
		$this->view->setVar('LoadJS', ['system/sys_admin.js']);
		// 视图
		$this->view->setTemplateAfter('main');
		return $this->view->pick("system/admin/index");
	}

	/* 搜索 */
	function searchAction(){
		return $this->view->pick('system/admin/sea');
	}

	/* 添加 */
	function addAction(){
		return $this->view->pick('system/admin/add');
	}
	function addDataAction(){
		// 是否有数据提交
		if($this->request->isPost()){
			// 采集数据
			$data=[
				'uname'=>trim($this->request->getPost('uname')),
				'password'=>md5($this->request->getPost('passwd')),
				'email'=>trim($this->request->getPost('email')),
				'tel'=>trim($this->request->getPost('tel')),
				'name'=>trim($this->request->getPost('name')),
				'department'=>trim($this->request->getPost('department')),
				'position'=>trim($this->request->getPost('position')),
				'rtime'=>date('Y-m-d H:i:s')
			];
			// 验证
			$res = Safety::isRight('uname',$data['uname']);
			if($res!==true){return $this->response->setJsonContent(['state'=>'n','msg'=>$res]);}
			$res = Safety::isRight('passwd',$this->request->getPost('passwd'));
			if($res!==true){return $this->response->setJsonContent(['state'=>'n','msg'=>$res]);}
			$res = Safety::isRight('email',$data['email']);
			if($res!==true){return $this->response->setJsonContent(['state'=>'n','msg'=>$res]);}
			$res = Safety::isRight('tel',$data['tel']);
			if($res!==true){return $this->response->setJsonContent(['state'=>'n','msg'=>$res]);}
			// 实例化
			$model = new SysAdmin();
			// 是否存在用户
			$isNull = $model->findFirst([
				"uname = :uname: OR email = :email: OR tel = :tel:",
				'bind' => array('uname'=>$data['uname'], 'email'=>$data['email'],'tel'=>$data['tel']),
				'columns'=>'id'
			]);
			if($isNull){
				return $this->response->setJsonContent(['state'=>'n','msg'=>'该用户名已经存在！']);
			}
			// 执行添加
			if($model->save($data)===false){
				return $this->response->setJsonContent(['state'=>'n','msg'=>'添加失败！']);
			}else{
				return $this->response->setJsonContent(['state'=>'y','url'=>'SysAdmins','msg'=>'添加成功！']);
			}
		}
	}

	/* 编辑 */
	function editAction(){
		// 数据
		$edit = SysAdmin::findFirst('id='.$this->request->getPost('id'));
		// 视图
		$this->view->setVar('edit',$edit);
		return $this->view->pick('system/admin/edit');
	}
	function editDataAction(){
		// 是否有数据提交
		if($this->request->isPost()){
			// 采集数据
			$data=[
				'name'=>trim($this->request->getPost('name')),
				'department'=>trim($this->request->getPost('department')),
				'position'=>trim($this->request->getPost('position'))
			];
			if($this->request->getPost('passwd')){
				$res = Safety::isRight('passwd',$this->request->getPost('passwd'));
				if($res!==true){return $this->response->setJsonContent(['state'=>'n','msg'=>$res]);}
				// 原密码判断
				$isNull =SysAdmin::findfirst([
					'id="'.$this->request->getPost('id').'" AND password="'.md5($this->request->getPost('passwd1')).'"',
					'columns'=>'id'
				]);
				if($isNull){
					$data['password'] = md5($this->request->getPost('passwd'));
				}else{
					return $this->response->setJsonContent(['state'=>'n','msg'=>'原密码错误！']);
				}
			}
			// 实例化
			$model = SysAdmin::findFirst([
				'id=:id:',
				'bind'=>['id'=>$this->request->getPost('id')]
			]);
			// 返回信息
			if($model->save($data)===false){
				return $this->response->setJsonContent(['state'=>'n','msg'=>'编辑失败！']);
			}else{
				return $this->response->setJsonContent(['state'=>'y','url'=>'SysAdmins','msg'=>'编辑成功！']);
			}
		}
	}

	/* 删除 */
	function delAction(){
		return $this->view->pick('system/admin/del');
	}
	function delDataAction(){
		// 是否有数据提交
		if($this->request->isPost()){
			$id = implode(',',json_decode($this->request->getPost('id')));
			// 数据
			$model = SysAdmin::find('id IN('.$id.')');
			if($model->delete()===false){
				return $this->response->setJsonContent(['state'=>'n','msg'=>'删除失败！']);
			}else{
				return $this->response->setJsonContent(['state'=>'y','url'=>'SysAdmins','msg'=>'删除成功！']);
			}
		}
	}

	/* 审核 */
	function auditAction(){
		return $this->view->pick('system/admin/audit');
	}
	function auditDataAction(){
		$id = implode(',',json_decode($this->request->getPost('id')));
		// 数据
		$model = SysAdmin::find('id IN('.$id.')');
		foreach($model as $val){
			// 数据
			$val->state = $this->request->getPost('state');
			// 更新
			if($val->save()===false){
				return $this->response->setJsonContent(['state'=>'n','msg'=>'审核失败！']);
			}
		}
		// 返回信息
		return $this->response->setJsonContent(['state'=>'y','url'=>'SysAdmins','msg'=>'审核成功！']);
	}

	/* 是否存在 */
	function isUnameAction(){
		$name = $this->request->getPost('name');
		$val = trim($this->request->getPost('val'));
		// 是否提交
		if(!$name || !$val){return false;}
		// 条件
		$where = '';
		if($name=='uname'){
			$where = 'uname="'.$val.'"';
		}elseif($name=='tel'){
			$where = 'tel="'.$val.'"';
		}elseif($name=='email'){
			$where = 'email="'.$val.'"';
		}
		// 查询
		if($where){
			$data = SysAdmin::findfirst([$where,'columns'=>'id']);
			return $data?$this->response->setJsonContent(['state'=>'y']):$this->response->setJsonContent(['state'=>'n']);
		}
	}

	/* 权限 */
	function permAction(){
		// 拆分权限
		$permArr=[];
		$arr = explode(' ',$_POST['perm']);
		foreach($arr as $val){
			$a=explode(':',$val);
			$permArr[$a[0]]=$a[1];
		}
		$this->view->setVar('permArr',$permArr);
		$this->view->setVar('Perm',SysMenuAction::find(['columns'=>'name,perm']));
		$this->view->setVar('Menus',$this->Menus());
		return $this->view->pick('system/admin/perm');
	}
	function permDataAction(){
		// 是否有数据提交
		if($this->request->isPost()){
			// 实例化
			$model = SysAdmin::findFirst('id='.$this->request->getPost('id'));
			$model->perm = trim($this->request->getPost('perm'));
			// 返回信息
			if($model->save()===false){
				return $this->response->setJsonContent(['state'=>'n','msg'=>'权限编辑失败！']);
			}else{
				return $this->response->setJsonContent(['state'=>'y','url'=>'SysAdmins']);
			}
		}
	}
	// 递归全部菜单
	private function Menus($fid='0'){
		$data=[];
		$M = SysMenus::find(['fid='.$fid,'columns'=>'id,title,perm']);
		foreach($M as $val){
			$val->menus = $this->Menus($val->id);
			$data[] = $val;
		}
		return $data;
	}
	
}