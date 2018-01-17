<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
/**
* 
*/
class ManageTimeTempTable extends Table
{
	public function initialize(array $config) {
        $this->setTable('manage_time_temp_tbl');
    }
    //insert to manage_time_temp_tbl
    public function insert($manageArr){
    	$manage = $this->newEntity();
    	$manage->employee_code = $manageArr[0];
    	$manage->employee_name = $manageArr[1];
		$manage->work_date = date('Y-m-d', strtotime($manageArr[2]));
		$manage->start_time = $manageArr[3];
		$manage->end_time = $manageArr[4];
        $manage->created_by = $manageArr[5];
        $this->save($manage);
    }
}