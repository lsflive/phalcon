<?php

use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Flash\Direct as Flash;

/**
* 注册：默认路由
*/
$di->setShared('router', function () {
	$router = new Router();
	$router->setDefaultModule('home');
	return $router;
});

/**
 * 公共类
 */
$di->setShared('inc', function () {
	return new \Phalcon\Library\Inc();
});

/**
* 注册：URL
*/
$di->setShared('url', function () {
	// 配置文件
	$config = $this->getConfig();
	// 公共类
	$inc = new \Phalcon\Library\Inc();
	// 设置网址
	$url = new UrlResolver();
	$url->setBaseUri($inc->BaseUrl().$config->application->baseUri);
	return $url;
});

/**
* 注册：session
*/
$di->setShared('session', function () {
	$session = new SessionAdapter();
	$session->start();
	return $session;
});

/**
* 注册：session 闪存
*/
$di->set('flash', function () {
	return new Flash([
		'error'=>'alert alert-danger',
		'success'=>'alert alert-success',
		'notice'=>'alert alert-info',
		'warning'=>'alert alert-warning'
	]);
});

/**
* Web：默认命名空间
*/
$di->setShared('dispatcher', function() {
	$dispatcher = new Dispatcher();
	$dispatcher->setDefaultNamespace('Phalcon\Modules\Home\Controllers');
	return $dispatcher;
});

/**
* 公共：volt模板
*/
$di->setShared('voltShared', function ($view) {
	$config = $this->getConfig();

	$volt = new VoltEngine($view, $this);
	$volt->setOptions([
		'compiledPath' => function($templatePath) use ($config) {
			$basePath = $config->application->appDir;
			if ($basePath && substr($basePath, 0, 2) == '..') {
				$basePath = dirname(__DIR__);
			}

			$basePath = realpath($basePath);
			$templatePath = trim(substr($templatePath, strlen($basePath)), '\\/');

			$filename = basename(str_replace(['\\', '/'], '_', $templatePath), '.volt') . '.php';

			$cacheDir = $config->application->cacheDir;
			if ($cacheDir && substr($cacheDir, 0, 2) == '..') {
				$cacheDir = __DIR__ . DIRECTORY_SEPARATOR . $cacheDir;
			}

			$cacheDir = realpath($cacheDir);

			if (!$cacheDir) {
				$cacheDir = sys_get_temp_dir();
			}

			if (!is_dir($cacheDir . DIRECTORY_SEPARATOR . 'volt' )) {
				@mkdir($cacheDir . DIRECTORY_SEPARATOR . 'volt' , 0755, true);
			}

			return $cacheDir . DIRECTORY_SEPARATOR . 'volt' . DIRECTORY_SEPARATOR . $filename;
		}
	]);

	return $volt;
});
