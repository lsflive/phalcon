<?php
namespace app\modules\admin;

use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface{

	/* 注册：自动加载模块 */
	public function registerAutoloaders(DiInterface $di=null){
		$loader = new Loader();
		$loader->registerNamespaces([
			'app\modules\admin\controller'=>__DIR__.'/controller/',
			'app\modules\admin\model'=>__DIR__.'/model/',
		]);
		$loader->register();
	}

	/* 注册：模块服务 */
	public function registerServices(DiInterface $di){
		// 注册：视图
		$di->set('view', function(){
			$view = new View();
			$view->setDI($this);
			$view->setViewsDir(__DIR__.'/view/');
			$view->registerEngines([
				'.volt'  => 'voltShared'
			]);
			return $view;
		});
		// 注册：公共类
		$di->set('inc', function(){
			return new \app\library\Inc();
		});
	}

}