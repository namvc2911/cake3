<?php



namespace App\Model\Table;

use Cake\ORM\Table;

class MembersTable extends Table {
    //put your code here

    public function initialize(array $config)
    {
        $this->setTable('member_project_tbl');
        
        // $this->hasMany('Users', [
        //     'className' => 'Users',
        //      'foreignKey' => 'user_id',
        // ]);
        $this->belongsTo('Projects', [
            'targetForeignKey' => 'project_id',
            'joinTable' => 'project_tbl',
            'foreignKey' => 'project_id',
        ]);
         $this->belongsTo('Users', [
            'targetForeignKey' => 'user_id',
            'joinTable' => 'user_tbl',
            'foreignKey' => 'user_id',
        ]);
         $this->belongsTo('Overtimes', [
            'targetForeignKey' => 'manage_overtime_id',
            'joinTable' => 'manage_overtime_tbl',
            'foreignKey' => 'user_id',
        ]);
         


    }

}
