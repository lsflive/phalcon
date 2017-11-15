<?php

namespace Phalcon\Modules\Admin\Controllers;

use Phalcon\Modules\Admin\Models\SysAdmin;
use Phalcon\Modules\Admin\Models\SysMenus;
use Phalcon\Modules\Admin\Models\SysMenuAction;

/**
* 后台：系统用户
*/
class SysAdminsController extends UserBase{
	/* 首页 */
	function indexAction(){
		// Page
		if(isset($_GET['search'])){
			$like = $this->pageWhere();
			$where = '';
			foreach ($like['data'] as $key => $val){
				$where .= $key." LIKE '%".$val."%' AND ";
			}
			$where = rtrim($where,'AND ');
			$data = SysAdmin::find(array($where,'order'=>'id desc'));
			$getUrl = $like['getUrl'];
		}else{
			$getUrl = '';
			$data = SysAdmin::find(array('order'=>'id desc'));
		}
		$this->view->setVar('Page',$this->getPage(array(
			'data'=>$data,
			'getUrl'=>$getUrl
		)));
		// 获取菜单
		$this->view->setVar('Menus',$this->getMenus());
		// JS
		$this->view->setVar('LoadJS', array('system/sys_admin.js'));
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
			
			// 实例化
			$model = new SysAdmin();
			// 是否存在用户
			$isNull = $model->findFirst('uname="'.$data['uname'].'"');
			if($isNull){
				return $this->response->setJsonContent(array('state'=>'n','msg'=>'该用户名已经存在！'));
			}
			// 执行添加
			if($model->save($data)===false){
				return $this->response->setJsonContent(array('state'=>'n','msg'=>'添加失败！'));
			}else{
				return $this->response->setJsonContent(array('state'=>'y','url'=>'SysAdmins','msg'=>'添加成功！'));
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
				'email'=>trim($this->request->getPost('email')),
				'tel'=>trim($this->request->getPost('tel')),
				'name'=>trim($this->request->getPost('name')),
				'department'=>trim($this->request->getPost('department')),
				'position'=>trim($this->request->getPost('position'))
			];
			if($this->request->getPost('passwd')){
				$data['password'] = md5($this->request->getPost('passwd'));
			}
			// 实例化
			$model = SysAdmin::findFirst(array('id=:id:','bind'=>array('id'=>$this->request->getPost('id'))));
			// 返回信息
			if($model->save($data)===false){
				return $this->response->setJsonContent(array('state'=>'n','msg'=>'编辑失败！'));
			}else{
				return $this->response->setJsonContent(array('state'=>'y','url'=>'SysAdmins','msg'=>'编辑成功！'));
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
				return $this->response->setJsonContent(array('state'=>'n','msg'=>'删除失败！'));
			}else{
				return $this->response->setJsonContent(array('state'=>'y','url'=>'SysAdmins','msg'=>'删除成功！'));
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
				return $this->response->setJsonContent(array('state'=>'n','msg'=>'审核失败！'));
			}
		}
		// 返回信息
		return $this->response->setJsonContent(array('state'=>'y','url'=>'SysAdmins','msg'=>'审核成功！'));
	}

	/* 权限 */
	function permAction(){
		// 权限数组
		$permArr = $this->splitPerm($this->request->getPost('perm'));
		// 所有动作
		$actionM = SysMenuAction::find();
		// 所有菜单
		$html = '';
		// 一级菜单
		$menu1 = SysMenus::find('fid=0');
		foreach($menu1 as $m1){
			$ck = isset($permArr[$m1->id])?'checked':'';
			$html .= '<div id="oneMenuPerm" class="perm">'."\n";
			$html .= '    <span class="text1"><input type="checkbox" value="'.$m1->id.'" '.@$ck.' /></span>'."\n";
			$html .= '    <span>[<a href="#">-</a>] '.$m1->title.'</span>'."\n";
			$html .= '</div>'."\n";
			// 二级菜单
			$menu2 = SysMenus::find('fid='.$m1->id);
			foreach($menu2 as $m2){
				$ck = isset($permArr[$m2->id])?'checked':'';
				$html .= '<div id="twoMenuPerm" class="perm">'."\n";
				$html .= '    <span class="text2"><input type="checkbox" value="'.$m2->id.'" '.@$ck.' /></span>'."\n";
				$html .= '    <span>[<a href="#">-</a>] '.$m2->title.'</span>'."\n";
				$html .= '</div>';
				// 三级菜单
				$menu3 = SysMenus::find('fid='.$m2->id);
				foreach($menu3 as $m3){
					$ck = isset($permArr[$m3->id])?'checked':'';
					$html .= '<div id="threeMenuPerm" class="perm perm_action">'."\n";
					$html .= '      <span class="text3"><input type="checkbox" name="threeMenuPerm" value="'.$m3->id.'" '.@$ck.' /></span>'."\n";
					$html .= '      <span>[<a href="#">-</a>] '.$m3->title.'</span>'."\n";
					$html .= '  <span id="actionPerm_'.$m3->id.'"> ( ';
					// 动作菜单
					foreach($actionM as $val){
						if(intval($m3->perm) & intval($val->perm)){
							$ck = @$permArr[$m3->id]&intval($val->perm)?'checked':'';
							$html .= '<span><input type="checkbox" value="'.$val->perm.'" '.@$ck.' /></span><span class="text">'.$val->name.'</span>';
						}
					}
					$html .= ')</span>';
					$html .= '</div>';
				}
			}
		}
		// 视图
		$this->view->setVar('permHtml', $html);
		return $this->view->pick('system/admin/perm');
	}
	/* 拆分权限 */
	private function splitPerm($perm){
		if($perm){
			$arr = explode(' ', $perm);
			foreach($arr as $val) {
				$num = explode(':', $val);
				$permArr[$num[0]]= $num[1];
			}
			return $permArr;
		}else{return FALSE;}
	}
	function permDataAction(){
		// 是否有数据提交
		if($this->request->isPost()){
			// 实例化
			$model = SysAdmin::findFirst('id='.$this->request->getPost('id'));
			$model->perm = trim($this->request->getPost('perm'));;
			// 返回信息
			if($model->save()===false){
				return $this->response->setJsonContent(array('state'=>'n','msg'=>'权限编辑失败！'));
			}else{
				return $this->response->setJsonContent(array('state'=>'y','url'=>'SysAdmins'));
			}
		}
	}
}