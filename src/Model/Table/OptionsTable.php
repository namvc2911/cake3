<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class OptionsTable extends Table {

    public function initialize(array $config) {
        $this->setTable('option_mst');
    }

}
