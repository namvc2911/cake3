<?php
namespace App\Model\Table;
use Cake\ORM\Table;

/**
* 
*/
class Role_mstTable extends Table
{
	
	public function initialize(array $config)
	{
		$this->setTable('role_mst');
		$this->belongsTo('Users',[
			'foreignKey' => 'role_id'
		]);
	}
}