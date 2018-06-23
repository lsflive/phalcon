<?php

namespace Phalcon\Modules\Admin\Controllers;

use Phalcon\Library\Images;
use Phalcon\Modules\Admin\Models\SysAdmin;

/**
* 后台：登录
*/
class IndexController extends ControllerBase{
	
	/* 首页 */
	public function indexAction(){
		$this->view->setTemplateAfter('login');
	}

	/* 登录 */
	public function loginAction(){
		// 是否有提交
		if(!$this->request->isPost()) return false;
		// 用户信息
		$uname = trim($this->request->getPost('uname'));
		$password = md5($this->request->getPost('passwd'));
		$vcode = strtolower($this->request->getPost('vcode'));
		$remember = $this->request->getPost('remember');
		// 判断验证码
		if($vcode != $this->session->get('V_CODE')){
			return $this->response->setJsonContent(['status'=>'v','msg'=>'验证码错误！']);
		}else{
			$this->session->set('V_CODE',rand(1000,9999));
		}
		// 实例化模型
		$data = SysAdmin::findFirst([
			"(uname = :uname: OR email = :uname: OR tel = :uname:) AND password = :password:",
			'bind' => ['uname'=>$uname, 'password'=>$password]
		]);
		// 判断结果
		if(empty($data)) return $this->response->setJsonContent(['status'=>'n','msg'=>'用户名或密码错误！']);
		// 是否禁用
		if($data->state!='1') return $this->response->setJsonContent(['status'=>'n','msg'=>'该用户已被禁用！']);
		// 记住用户名
		if($remember=='true') setcookie("uname", $uname);
		// 保存SESSION
		$this->session->set('Admin',[
			'id'=>$data->id,
			'uname'=>$uname,
			'name'=>$data->name,
			'department'=>$data->department,
			'position'=>$data->position,
			'ltime'=>time()+1800,
			'login'=>TRUE,
		]);
		// 返回跳转URL
		return $this->response->setJsonContent(['status'=>'y','url'=>'welcome']);
	}

	/* 退出 */
	public function logoutAction(){
		$this->session->remove('Admin');
		$this->redirect('index');
	}

	/* 验证码 */
	function vcodeAction(){
		Images::getCode(90,36);
	}
}