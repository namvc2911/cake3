<?php
namespace App\Controller\Component;
use Cake\Controller\Component;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use ManageTimeTempTable;
use Cake\ORM\TableRegistry;
require_once(ROOT . DS . 'vendor' . DS . 'PHPExcel.php');
require_once(ROOT . DS . 'vendor' . DS . 'PHPExcel' . DS . 'IOFactory.php');
class PhpExcelComponent extends Component
{
	public $components = ['Auth'];
	public function import($file){
		$check = new PHPExcel();//use library PHPExcel
		$check = PHPExcel_IOFactory::load($file);
		$check->setActiveSheetIndex(0);
		$i = 2;
		while ($check->getActiveSheet()->getCell('A'.$i)->getValue() !="") {
			$employee_code = $check->getActiveSheet()->getCell('A'.$i)->getValue();
			$employee_name = $check->getActiveSheet()->getCell('B'.$i)->getValue();
			$work_date = $check->getActiveSheet()->getCell('D'.$i)->getFormattedValue();
			$time1 = $check->getActiveSheet()->getCell('E'.$i)->getFormattedValue();
			$start_time =$time1;
			$end_time = '';
			$j = 'E';
			while ($check->getActiveSheet()->getCell($j.$i)->getFormattedValue() !="") {
				$end_time = $check->getActiveSheet()->getCell($j.$i)->getFormattedValue();
				$j++;
			}
			$user = $this->Auth->user();
			$user_id = $user['user_id'];
			$manage = array($employee_code, $employee_name, $work_date, $start_time, $end_time, $user_id);
			$manageTimeTempTable = TableRegistry::get('ManageTimeTemp');
			$manageTimeTempTable->insert($manage);
			$i++;
		}
	}
}