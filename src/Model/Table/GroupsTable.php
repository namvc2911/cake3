<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class GroupsTable extends Table {
	public function initialize(array $config){
		$this->setTable('group_tbl');
	}
	public function validationGroupName(Validator $validator) {
        return $validator
        				->requirePresence('name')
                        ->notEmpty('name', 'Tên nhóm không được rỗng');
    }
}