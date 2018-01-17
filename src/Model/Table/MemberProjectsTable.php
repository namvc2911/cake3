<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Search\Manager;


class MemberProjectsTable extends Table {
    
    public function initialize(array $config) {
        $this->addBehavior('Timestamp');
        $this->setTable('member_project_tbl');
    }

    public function getdata($user_id){
        $query = $this->find('all')->WHERE(['user_id ='=>$user_id]);
        $info = $query->toArray();
        $project_id_arr = [];
        foreach ($info as $v) {
            $project_id_arr[] = $v->project_id;
        }
        return $project_id_arr;
    }
    public function getMemberProject($project_id){
        $query = $this->find('all')->WHERE(['project_id IN'=>$project_id]);
        $info = $query->toArray();
        $user_id_arr = [];
        foreach ($info as $v ) {
          $user_id_arr[] = $v->user_id;
        }
    return $user_id_arr;

    }
    public function getLeaderProject($project_id){
        $query = $this->find('all')->WHERE(['project_id IN'=>$project_id])->andWHERE(['is_leader'=>1]);
     
        $info = $query->toArray();
        $user_id_is_leader = [];
        foreach ($info as $v ) {
          $user_id_is_leader[] = $v->user_id;
        }
        
        return $user_id_is_leader;
    }
    
    public function validationDefault(Validator $validator) {

        return $validator
                        ->notEmpty('username', 'A username is required')
                        ->notEmpty('password', 'A password is required')
        ;
    }
}
