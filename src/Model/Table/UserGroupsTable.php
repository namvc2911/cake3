<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class UserGroupsTable extends Table {
	public function initialize(array $config){
		$this->setTable('user_group_tbl');
	}
}