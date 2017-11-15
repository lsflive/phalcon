<?php

use Phalcon\Loader;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;

/**
* 公共：配置文件
*/
$di->setShared('config', function () {
	return include APP_PATH . "/config/config.php";
});

/**
* 公共：数据库链接
*/
$di->setShared('db', function () {
	$config = $this->getConfig();

	$class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
	$params = [
	'host'     => $config->database->host,
	'username' => $config->database->username,
	'password' => $config->database->password,
	'dbname'   => $config->database->dbname,
	'charset'  => $config->database->charset
	];

	if ($config->database->adapter == 'Postgresql') {
		unset($params['charset']);
	}

	$connection = new $class($params);

	return $connection;
});

/**
* 元数据适配器
*/
$di->setShared('modelsMetadata', function () {
	return new MetaDataAdapter();
});