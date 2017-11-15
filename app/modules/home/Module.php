<?php
namespace Phalcon\Modules\Home;

use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface{

	/**
	* 注册：自动加载模块
	*/
	public function registerAutoloaders(DiInterface $di = null){

		$loader = new Loader();

		$loader->registerNamespaces([
			'Phalcon\Modules\Home\Controllers' => __DIR__ . '/controllers/',
			'Phalcon\Modules\Home\Models' => __DIR__ . '/models/',
		]);

		$loader->register();
	}

	/**
	* 注册：模块相关的服务
	*/
	public function registerServices(DiInterface $di){
		/**
		* 注册：视图
		*/
		$di->set('view', function () {
			$view = new View();
			$view->setDI($this);
			$view->setViewsDir(__DIR__ . '/views/');

			$view->registerEngines([
				'.volt'  => 'voltShared',
				'.phtml' => PhpEngine::class
			]);

			return $view;
		});
	}

}
