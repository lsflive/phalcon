<?php

namespace Phalcon\Modules\Admin\Models;

use Phalcon\Mvc\Model;

class SysAdmin extends Model{
	public $id;
	public function getSource(){
		return "sys_admin";
	}
}
