<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * Description of MemberProjectTable
 *
 * @author manht
 */
class MemberProjectTable extends Table {

    //put your code here

    public function initialize(array $config) {
        $this->setTable('member_project_tbl');
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
        $this->belongsTo('Projects', [
            'foreignKey' => 'project_id',
            'className' => 'Projects',
            'joinType' => 'INNER',
        ]);
    }

    public function validationMember(Validator $validator) {
        $user = TableRegistry::get('Users');
        $query = $user->find()->select(['user_id']);
        $arrUserID = [];
        foreach ($query as $row) {
            array_push($arrUserID, $row->user_id);
        }
        return $validator
                        ->add('members', 'inList', [
                            'rule' => ['inList', $arrUserID],
                            'message' => 'Sai thành viên'
                        ])
                        ->add('leader', 'inList', [
                            'rule' => ['inList', $arrUserID],
                            'message' => 'Sai thành viên'
                        ]);
    }

}
