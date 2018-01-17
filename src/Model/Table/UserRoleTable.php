<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class UserRoleTable extends Table {

    public function initialize(array $config) {
        $this->setTable('user_role_tbl');
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'create_time' => 'new',
                    'update_time' => 'always',
                ]
            ]
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'className' => 'Users',
            'joinType' => 'INNER',
        ]);
         $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'className' => 'Roles',
            'joinType' => 'INNER',
        ]);
    }

    public function validationRoles(Validator $validator) {
        $roles = TableRegistry::get('Roles');
        $query = $roles->find()->select(['role_id']);
        $arrRolesID = [];
        foreach ($query as $row) {
            array_push($arrRolesID, $row->role_id);
        }
        return $validator
                        ->add('userRoles', 'inList', [
                            'rule' => ['inList', $arrRolesID],
        ]);
    }

}
