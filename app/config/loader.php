<?php

use Phalcon\Loader;

$loader = new Loader();

/* 注册：命名空间 */
$loader->registerNamespaces([
	'app\library'  => APP_PATH.'/library/',
	'app\model'  => APP_PATH.'/model/',
]);
$loader->register();
