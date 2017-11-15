<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {
	// 注册堆栈框架
	$di = new FactoryDefault();
	// 公共服务
	require APP_PATH . '/config/services.php';
	// 公共服务：web
	require APP_PATH . '/config/services_web.php';
	// 自动加载
	include APP_PATH . '/config/loader.php';

	// 配置信息
	$config = $di->getConfig();
	
	// 处理请求
	$app = new Application($di);

	// 注册：模块
	$app->registerModules([
		'home' => ['className' => 'Phalcon\Modules\Home\Module'],
		'admin' => ['className' => 'Phalcon\Modules\Admin\Module'],
	]);

	// 注册：路由
	require APP_PATH . '/config/routes.php';
	
	// 执行
	echo $app->handle()->getContent();
	
} catch (\Exception $e) {
	echo $e->getMessage() . '<br>';
	echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
