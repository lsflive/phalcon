<?php

use Phalcon\Session\Adapter\Files as SessionAdapter;

/**
* 注册：session
*/
$di->setShared('session', function () {
	$session = new SessionAdapter();
	$session->start();
	return $session;
});
