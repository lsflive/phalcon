<?php

namespace Phalcon\Modules\Admin\Controllers;

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
		if($this->request->isPost()){
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
			if(isset($data)){
				// 是否禁用
				if($data->state=='1'){
					// 记住用户名
					if($remember=='true'){
						setcookie("uname", $uname);
					}
					// 保存用户信息到Session
					$this->_registerSession($data,$uname);
					// 返回跳转URL
					return $this->response->setJsonContent(['status'=>'y','url'=>'welcome']);
				}else{
					return $this->response->setJsonContent(['status'=>'n','msg'=>'该用户已被禁用！']);
				}
			}else{
				return $this->response->setJsonContent(['status'=>'n','msg'=>'用户名或密码错误！']);
			}
		}
	}
	// 保存Session
	private function _registerSession($data,$uname){
		// 保存用户信息
		$this->session->set('Admin', array(
			'id'=>$data->id,
			'uname'=>$uname,
			'name'=>$data->name,
			'department'=>$data->department,
			'position'=>$data->position,
			'ltime' => time()+1800,
			'logged_in' => TRUE,
		));
	}

	/* 退出 */
	public function loginOutAction(){
		$this->session->remove('Admin');
		$this->redirect('index');
	}

	/* 验证码 */
	function vcodeAction(){
		/* 第三方类 */
		$code = new \Phalcon\Library\Images();
		// 验证码
		$code->getCode(80,23);
	}
}