<?php
/*
* WebMIS开源项目
* Author: admin@webmis.vip
*/
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

// 开启
@session_start();

return new \Phalcon\Config([
	'version' => '1.0.0',
	// 数据库配置
	'database' => [
		'adapter'=>'Mysql',
		'host'=>'localhost',
		'username'=>'webmis',
		'password'=>'webmis',
		'dbname'=>'mvc',
		'charset'=>'utf8',
	],
	// APP配置
	'application' => [
		'appDir'=>APP_PATH.'/',
		'modelsDir'=> APP_PATH.'/common/models/',
		'migrationsDir'=>APP_PATH.'/migrations/',
		'cacheDir'=> BASE_PATH.'/cache/',
		// 项目路径
		'baseUri'=>'/',
		// 'baseUri'=>$_SERVER["PHP_SELF"].'?_url=/',
	],
	// CLI结果新行
	'printNewLine' => true
]);
