<?php

use Phalcon\Mvc\Url as UrlResolver;

/**
* 公共：数据库链接
*/
$di->setShared('db', function () {
	// 配置文件
	$config = $this->getConfig();
	// 参数
	$params = [
		'host'=>$config->database->host,
		'username'=>$config->database->username,
		'password'=>$config->database->password,
		'dbname'=>$config->database->dbname,
		'charset'=>$config->database->charset
	];
	// 删除编码
	if ($config->database->adapter == 'Postgresql') unset($params['charset']);
	// 命名空间
	$class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
	return new $class($params);
});

/**
* 注册：URL
*/
$di->setShared('url', function () {
	// 配置文件
	$config = $this->getConfig();
	// 公共类
	$inc = new \app\library\Inc();
	// 设置网址
	$url = new UrlResolver();
	$url->setBaseUri($inc->BaseUrl().$this->getDispatcher()->getModuleName().'/'.$config->application->baseUri);
	return $url;
});