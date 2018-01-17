<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class UserMetaTable extends Table {

    public function initialize(array $config) {
        $this->setTable('usermeta_tbl');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'className' => 'Users',
            'joinType' => 'INNER',
        ]);
    }

}
