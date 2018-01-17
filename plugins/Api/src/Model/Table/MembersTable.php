<?php

namespace Api\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class MembersTable extends Table {

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
    }

    public function validationAdd(Validator $validator) {

        $project = TableRegistry::get('Api.Projects');
        $query_project = $project->find()->select(['project_id']);
        $arrProjectId = [];
        foreach ($query_project as $row) {
            array_push($arrProjectId, $row->project_id);
        }

        $user = TableRegistry::get('Api.Users');
        $query_user = $user->find()->select(['user_id']);
        $arrUserId = [];
        foreach ($query_user as $row) {
            array_push($arrUserId, $row->user_id);
        }

        $validator
                ->requirePresence('user_id')
                ->notEmpty('user_id')
                ->add('user_id', 'inList', [
                    'rule' => ['inList', $arrUserId],
                    'message' => 'Sai thành viên'
                ])
                ->requirePresence('is_leader')
                ->notEmpty('is_leader')
                ->add('is_leader', 'inList', [
                    'rule' => ['inList', ['0', '1']],
                    'message' => 'Sai trạng thái'])
                ->requirePresence('project_id')
                ->notEmpty('project_id')
                ->add('project_id', 'inList', [
                    'rule' => ['inList', $arrProjectId],
                    'message' => 'Sai dự án'
        ]);
        return $validator;
    }

    // Thu lay thong tin thanh vien du an
    public function getInfoMember($data = []) {

        $projects = isset($data['projects']) ? $data['projects'] : '';

        if (empty($projects)) {
            return null;
        }

        foreach ($projects as $project) {

            $query = $this->find()->select([
                        'Members.user_id',
                        'fullname' => 'user.fullname',
                        'Members.is_leader'
                    ])->join([
                'table' => 'user_tbl',
                'alias' => 'user',
                'type' => 'LEFT',
                'conditions' => 'user.user_id = Members.user_id',
            ]);

            $query->where([
                'Members.project_id IN ' => $project['project_id']
            ]);

            $project['members'] = $query;
        }

        $data = $projects;
        $total = $data->count();

        return array(
            'http' => 200,
            'message' => 'success',
            'projects' => $data,
            'total' => $total
        );
    }

    //phuong lay thong tin thanh vien du an
    public function getAllMember($data = []) {
        $projects = isset($data['projects']) ? $data['projects'] : '';
        if ($projects == "") {
            return null;
        }

        foreach ($projects as $project) {

            $query1 = $this->find()->select([
                        'Members.member_project_id',
                        'Members.user_id',
                        'fullname' => 'user.fullname',
                        'Members.is_leader',
                    ])
                    ->join([
                        'table' => 'user_tbl',
                        'alias' => 'user',
                        'type' => 'LEFT',
                        'conditions' => 'user.user_id = Members.user_id'
                    ])
                    ->join([
                'table' => 'project_tbl',
                'alias' => 'project',
                'type' => 'LEFT',
                'conditions' => 'project.project_id = Members.project_id']
            );

            $query1->where([
                'Members.project_id IN' => $project['project_id']
            ]);
            $project['members'] = $query1;
        }
        $data = $projects;
        $total = $data->count();
        return array(
            'http' => 200,
            'message' => 'success',
            'projects' => $data,
            'total' => $total
        );
    }

}
