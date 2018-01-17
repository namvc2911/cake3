<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class User_roleTable extends Table {
    public function initialize(array $config) {
        $this->setTable('user_role_tbl');
        $this->hasMany('Users', [
            'className' => 'Users',
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('role_mst', [
        	'className' => 'value',
        	'foreignKey' => 'role_id'
        ]);
    }
}
