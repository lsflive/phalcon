<?php

namespace Phalcon\Modules\Admin\Models;

use Phalcon\Mvc\Model;

class SysMenus extends Model{
	public $id;
	public function getSource(){
		return "sys_menus";
	}
}
