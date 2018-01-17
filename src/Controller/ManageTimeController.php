<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class ManageTimeController extends AppController {
	public function initialize() {
		parent::initialize();
		$this->viewBuilder()->setLayOut('my_layout');
		$this->loadComponent('PhpExcel');
	}
	public function importExcel() {
		if ($this->request->is('post')) {
			$file = $this->request->data();
			if (!empty($file['importFile']['name'])) {
				$filename = $file['importFile']['name'];
				$this->PhpExcel->import($filename);
				return $this->redirect(['controller' => 'ManageTime', 'action' => 'confirmExcel']);
			} else {
				$this->Flash->error(__('No file has been choosen'));
			}
		}
	}
	public function confirmExcel() {
		$manageTimeTempTable = TableRegistry::get('ManageTimeTemp');
		$manageTimeArrayResult = $manageTimeTempTable->find('all', ['group' => 'employee_code']);
		$this->set(compact('manageTimeArrayResult'));
	}
	public function insert() {
		$this->autoRender = false;
		$manage = [];
		$user = $this->Auth->user();
		$user_id = $user['user_id'];
		$userTable = TableRegistry::get('Users');
		$manageTimeTempTable = TableRegistry::get('ManageTimeTemp');
		$manageTimeTable = TableRegistry::get('ManageTime');
		$getUser = $userTable->find()->combine('employee_code', 'user_id')->toArray();
		$employee_code_temp = array_keys($getUser);
		$manageTime = $manageTimeTempTable->find()->select(['employee_code', 'work_date', 'start_time', 'end_time'])->where(['employee_code IN' => $employee_code_temp])->toArray();
		foreach ($manageTime as $value) {
			$getUser_id = $getUser[$value->employee_code];
			$manage = array($getUser_id, $value->work_date, $value->start_time, $value->end_time);
			$dataMain = $manageTimeTable->find()->select(['manage_time_id'])->where(['user_id IN' => $getUser_id, 'work_date IN' => $value->work_date])->hydrate(false)->toArray();
			if (!empty($dataMain)) {
				foreach ($dataMain as $manage_temp_id) {
					$id = $manage_temp_id['manage_time_id'];
					$manageTimeTable->update($id);
				}
			}
			$manageTimeTable->insert($manage);
		}
		$manageTimeTempTable->query()->delete()->where(['created_by' => $user_id])->execute();
		$this->redirect(['controller' => 'ManageTime', 'action' => 'finishImport']);
	}
	//get data group_by employee_code
	public function detail($employee_code) {
		$manageTimeTempTable = TableRegistry::get('ManageTimeTemp');
		$manageTimeArrayResult = $manageTimeTempTable->find()
			->where(['employee_code' => $employee_code])
			->order(['work_date' => 'ASC']);
		$this->set(compact('manageTimeArrayResult'));
	}
	public function finishImport() {

	}
	//delete manage_time_temp_tbl
	public function deleteTemp() {
		if ($this->Auth->user()['role'] == 'HR') {
			$user = $this->Auth->user();
			$user_id = $user['user_id'];
			$manageTimeTempTable = TableRegistry::get('ManageTimeTemp');
			$manageTimeTempTable->query()->delete()->where(['created_by' => $user_id])->execute();
			$this->redirect(['controller' => 'ManageTime', 'action' => 'importExcel']);
		} else {
			echo "Bạn không đủ quyền";
			$this->autoRender = false;
		}
	}
}
