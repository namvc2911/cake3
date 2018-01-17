<?php

namespace Api\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class ProjectsTable extends Table {

    public function initialize(array $config) {
        $this->table('project_tbl');
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'create_time' => 'new',
                    'update_time' => 'always',
                ]
            ]
        ]);
    }

    public function formatStartDate() {
        $query = $this->find();
        $start_date = $query->func()->date_format([
            'Projects.start_date' => 'identifier',
            "'%Y-%m-%d'" => 'literal'
        ]);
        return $start_date;
    }

    public function formatEndDate() {
        $query = $this->find();
        $end_date = $query->func()->date_format([
            'Projects.end_date' => 'identifier',
            "'%Y-%m-%d'" => 'literal'
        ]);
        return $end_date;
    }

    //Thu: xem danh sach du an da va dang tham gia
    public function getProjects($data = []) {

        $user_id = isset($data['user_id']) ? $data['user_id'] : '';

        if ($user_id == "") {
            return null;
        }

        $query = $this->find();
        $start_date = $this->formatStartDate();
        $end_date = $this->formatEndDate();

        $query = $this->find()
                ->select([
                    'Projects.project_id',
                    'Projects.name',
                    'Projects.status',
                    'start_date' => $start_date,
                    'end_date' => $end_date
                ])->join([
                    'table' => 'member_project_tbl',
                    'alias' => 'Members',
                    'type' => 'LEFT',
                    'conditions' => 'Projects.project_id = Members.project_id'
                ])->where(['Members.user_id' => $user_id])
                ->order(['name' => 'ASC']);

        $total = $query->count();

        return array(
            'http' => 200,
            'message' => 'success',
            'projects' => $query,
            'total' => $total
        );
    }

    //Phuong danh sach du an
    public function getProject() {

        $query = $this->find()->order(['name' => 'ASC']);
        $total = $query->count();
        return array(
            'http' => 200,
            'message' => 'success',
            'projects' => $query,
            'total' => $total
        );
    }

    //Phuong lay thong tin du an
    public function getAllProject() {
        $start_date = $this->formatStartDate();
        $end_date = $this->formatEndDate();
        $query = $this->find()->select([
                    'Projects.project_id',
                    'Projects.name',
                    'Projects.status',
                    'start-date' => $start_date,
                    'end-date' => $end_date])
                ->order(['name' => 'ASC']);

        $query->toArray();
        return array(
            'http' => 200,
            'message' => 'sucess',
            'projects' => $query
        );
    }

    public function validationAdd(Validator $validator) {
        $validator
                ->requirePresence('name')
                ->notEmpty('name')
                ->notEmpty('description')
                ->requirePresence('status')
                ->notEmpty('status')
                ->add('status', 'inList', [
                    'rule' => ['inList', ['1', '2', '3']],
                    'message' => 'Sai trạng thái dự án']
                )
                ->requirePresence('start_date')
                ->notEmpty('start_date')
                ->add('start_date', [
                    'date' => [
                        'rule' => ['date']
                    ]
                ])
                ->allowEmpty('end_date')
                ->add('end_date', [
                    'date' => [
                        'rule' => ['date']
                    ],
                    'custom' => [
                        'rule' => function ($value, $context) {
                            if (isset($context['data']['start_date'])) {
                                return ($context['data']['start_date'] <= $context['data']['end_date']);
                            }
                            return true;
                        },
                        'message' => 'Sai ngày kết thúc']
        ]);
        return $validator;
    }

    public function validationEdit(Validator $validator) {
        $validator
                ->requirePresence('project_id')
                ->notEmpty('project_id')
                ->notEmpty('name')
                ->add('status', 'inList', [
                    'rule' => ['inList', ['1', '2', '3']],
                    'message' => 'Sai trạng thái dự án']
                )
                ->notEmpty('start_date')
                ->allowEmpty('end_date')
                ->add('end_date', 'custom', [
                    'rule' => function ($value, $context) {
                        if (isset($context['data']['start_date'])) {
                            return ($context['data']['start_date'] <= $context['data']['end_date']);
                        }
                        return true;
                    },
                    'message' => 'Sai ngày kết thúc']);
        return $validator;
    }

}
