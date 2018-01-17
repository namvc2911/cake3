<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;
/**
* 
*/
class ManageTimeTable extends Table
{
	public function initialize(array $config) {
        
        $this->setTable('manage_time_tbl');  
    }
    public function update($id){
        $updateTime = $this->get($id);
        $this->delete($updateTime); 
    }
    public function insert($manageArr){
        $insertTime = $this->newEntity();
        $insertTime->manage_time_id = Text::uuid();
        $insertTime->user_id = $manageArr[0];
        $insertTime->work_date = $manageArr[1];
        $insertTime->start_time = $manageArr[2];
        $insertTime->end_time = $manageArr[3];
        $this->save($insertTime);  
    }
}