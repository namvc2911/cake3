<?php
namespace Api\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

class UsersTable extends Table{
	
	public function initialize(array $config){

		$this->addBehavior('Timestamp');
		$this->table('user_tbl');
		$this->hasMany('Overtimes',
			array(
				'foreignKey'=>'user_id',
				'bindingKey' => 'user_id',
				'className'=>'Api.Overtimes',
				
				)
		);
	}

// Xem thong tin lam them gio(test tren web)
	public function getOvertimes($user_id=null){
		$query = $this->find()->select([
				'Users.user_id',
				'Users.fullname',
				'day_of_overtime'=>'Overtimes.day_of_overtime',
				'reason'=>'Overtimes.reason',
				'approve_status'=>'Overtimes.approve_status',
				'approver'=>'Overtimes.approver',
				])
				->join([
		        'table' => 'manage_overtime_tbl',
		        'alias' => 'overtimes',
		        'type' => 'LEFT',
		        'conditions' => 'Users.user_id = overtimes.user_id',
		    ]);
		if($user_id)
		{
				$query->where(['Users.user_id'=>$user_id]);
		}		
		$total = $query->count();
		return array(
				'http'=>200,
				'message'=>'success',
				'overtimes' => $query,
				'total' => $total
		);
	}	
}

	