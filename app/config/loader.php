<?php

use Phalcon\Loader;

$loader = new Loader();

/**
 * 注册：命名空间
 */
$loader->registerNamespaces([
	'Phalcon\Library'  => APP_PATH . '/library/',
]);

/**
 * 注册：类
 */
$loader->registerClasses([
	'Phalcon\Modules\Admin\Module'  => APP_PATH . '/modules/admin/Module.php',
	'Phalcon\Modules\Home\Module'  => APP_PATH . '/modules/home/Module.php'
]);

$loader->register();
