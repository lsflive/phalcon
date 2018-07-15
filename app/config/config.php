<?php
/*
* WebMIS开源项目
* Author: admin@webmis.vip
*/
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

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
		'cacheDir'=> BASE_PATH.'/cache/',
		'baseUri'=>'',
	],
	// CLI结果新行
	'printNewLine' => true
]);
