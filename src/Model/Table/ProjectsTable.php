<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Description of ProjectsTable
 *
 * @author manht
 */
class ProjectsTable extends Table {

    //put your code here


    public function initialize(array $config) {
        
        $this->setTable('project_tbl');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'create_time' => 'new',
                    'update_time' => 'always',
                ]
            ]
        ]);
        $this->belongsToMany('Users', [
            'targetForeignKey' => 'user_id',
            'joinTable' => 'member_project_tbl',
            'foreignKey' => 'project_id',
        ]);

        $this->hasMany('Members')
        ->setForeignKey('project_id')
            ->setDependent(true);
        $this->hasMany('Overtimes')
            ->setForeignKey('project_id')
            ->setDependent(true);

    }
    
    public function getData($project_id,$conditions=null){

        $query = $this->find('all')->order(['name'])
                ->where(['project_id IN'=>$project_id])
                ->andWhere($conditions);
        return $query;
    }
   
    public function validationAdd(Validator $validator) {
        return $validator
                        ->notEmpty('name', 'Tên dự án không được rỗng')
                        ->notEmpty('start_date', 'Ngày bắt đầu không được rỗng')
                        ->add('status', 'inList', [
                            'rule' => ['inList', ['1', '2', '3']],
                            'message' => 'Sai trạng thái dự án'])
                        ->allowEmpty('end_date')
                        ->add('end_date', 'custom', [
                            'rule' => function ($value, $context) {
                                return ($context['data']['start_date'] <= $context['data']['end_date']);
                            },
                            'message' => 'Sai ngày kết thúc']);
    }

}
