<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class RolesTable extends Table {
    public function initialize(array $config) {
        $this->setTable('role_mst');

      $this->belongsToMany('Users', [
            'targetForeignKey' => 'user_id',
            'joinTable' => 'user_role_tbl',
            'foreignKey' => 'user_id',
        ]);
    }
}


