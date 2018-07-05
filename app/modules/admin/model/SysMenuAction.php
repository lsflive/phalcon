<?php

namespace app\modules\admin\model;

use Phalcon\Mvc\Model;

class SysMenuAction extends Model{
	public $id;
	public function getSource(){
		return "sys_menus_action";
	}
}
