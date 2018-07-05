<?php

use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;

/**
* 注册：session
*/
$di->setShared('session', function () {
	$session = new SessionAdapter();
	$session->start();
	return $session;
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
