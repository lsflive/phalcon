<?php

namespace app\modules\admin\model;

use Phalcon\Mvc\Model;

class SysAdmin extends Model{
	public $id;
	public function getSource(){
		return "sys_admin";
	}
}
