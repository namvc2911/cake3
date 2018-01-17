<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//namespace scripts\Controller;

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\Query;
use Cake\Utility\Text;

/**
 * Description of UsersController
 *
 * @author manht
 */
class UsersController extends AppController {

	//put your code here
	public $roleUserLogin;
	public $isleaderUserLogin;

	public function initialize() {
		parent::initialize();
		$this->viewBuilder()->setLayout('my_layout');
	}

	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);
		$this->Auth->allow(['index', 'add', 'edit', 'delete']);
	}

	// Login
	public function login() {
		$this->viewBuilder()->setLayout('my_layout_login');
		if (!empty($this->Auth->user())) {
			return $this->redirect(['controller' => 'Home', 'action' => 'index']);
		}
		if ($this->request->is('post')) {
			if (empty($this->request->data['username']) && empty($this->request->data['password'])) {
				$this->Flash->myError('Nhập tên tài khoản và mật khẩu');
			} elseif (empty($this->request->data['username'])) {
				$this->Flash->myError('Nhập tài khoản');
			} elseif (empty($this->request->data['password'])) {
				$this->Flash->myError('Nhập mật khẩu');
			} else {
				$user = $this->Auth->identify();
				if ($user) {
					$user['role'] = $this->getRoleUserLogin($user);
					$this->Auth->setUser($user);
					return $this->redirect($this->Auth->redirectUrl());
				}
				$this->Flash->myError('Sai tên tài khoản hoặc mật khẩu');
			}
		}
	}

	//Logout
	public function logout() {
		return $this->redirect($this->Auth->logout());
	}

	// Get Role from User Login
	public function getRoleUserLogin($user = array()) {
		$this->loadModel('Users');
		$this->loadModel('UserRole');
		$this->loadModel('Roles');

		$userLogin = $this->Users->find()
			->select([
				'Users.user_id',
				'UserRole.user_id',
				'Role.role_id',
			])
			->join([
				array(
					'table' => 'user_role_tbl',
					'type' => 'LEFT',
					'alias' => 'UserRole',
					'conditions' => [
						'Users.user_id = UserRole.user_id',
					],
				),

			])
			->join([
				array(
					'table' => 'role_mst',
					'type' => 'LEFT',
					'alias' => 'Role',
					'conditions' => [
						'Role.role_id = UserRole.role_id',
					],
				),

			])->hydrate(false)->first();

		return $userLogin;

	}

	public function index() {
		$data = $this->request->query();
		$conditions = [];
		if ($this->request->is(['get'])) {
			$searchName = isset($data['fullname']) ? $data['fullname'] : '';
			if (isset($searchName) && $searchName) {
				$conditions['fullname LIKE'] = '%' . $searchName . '%';
			}
			$searchcode = isset($data['employee_code']) ? $data['employee_code'] : '';
			if (isset($searchcode) && $searchcode) {
				$conditions['employee_code LIKE'] = '%' . $searchcode . '%';
			}
		}

		$users = $this->Users->find('all', ['order' => ['Users.employee_code' => 'ASC']])
			->where($conditions)
			->contain(['Roles' => function ($q) {
				return $q->select(['Roles.value']);
			},
			]);

		try {
			$this->paginate($users);
		} catch (NotFoundException $ex) {
			return $this->redirect(['controller' => 'Home', 'action' => 'index']);
		}
		$this->set(compact('users', 'data'));
	}

	public function add() {
		$this->loadModel('UserRole');
		$this->loadModel('UserMeta');
		$roleUser = $this->UserRole->newEntity();
		$umeta = $this->UserMeta->newEntity();
		$user = $this->Users->newEntity();

		$this->loadModel('Roles');
		$roles = $this->Roles->find('list', [
			'keyField' => 'role_id',
			'valueField' => 'value',
			'order' => ['value' => 'ASC'],
		])->toArray();
		$this->set('roles', $roles);

		$this->loadModel('Options');
		$opt = $this->Options->find('list', [
			'keyField' => 'opt_key',
			'valueField' => 'opt_value',
		])->toArray();
		$this->set('opt', $opt);

		if ($this->request->is('post', 'put')) {
			if (!empty($this->request->data)) {
				if (isset($this->request->data['date_of_birth']) && !empty($this->request->data['date_of_birth'])) {
					$this->request->data['date_of_birth'] = date('Y-m-d', strtotime($this->request->data['date_of_birth']));
				}
				if (isset($this->request->data['trial_date']) && !empty($this->request->data['trial_date'])) {
					$this->request->data['trial_date'] = date('Y-m-d', strtotime($this->request->data['trial_date']));
				}
				if (isset($this->request->data['official_date']) && !empty($this->request->data['official_date'])) {
					$this->request->data['official_date'] = date('Y-m-d', strtotime($this->request->data['official_date']));
				}
				$image = $this->request->data['image'];

				$user = $this->Users->patchEntity($user, [
					'user_id' => Text::uuid(),
					'username' => $this->request->data['username'],
					'password' => $this->request->data['password'],
					'nickname' => $this->request->data['nickname'],
					'personal_email' => $this->request->data['personal_email'],
					'company_email' => $this->request->data['company_email'],
					'fullname' => $this->request->data['fullname'],
					'employee_code' => $this->request->data['employee_code'],
					'slogan' => $this->request->data['slogan'],
					'gender' => $this->request->data['gender'],
					'image' => $this->request->data['image'],
					'identity_no' => $this->request->data['identity_no'],
					'date_of_birth' => $this->request->data['date_of_birth'],
					'address' => $this->request->data['address'],
					'trial_date' => $this->request->data['trial_date'],
					'official_date' => $this->request->data['official_date'],
				], ['validate' => 'Add']);

				$isError = false;
				$conn = ConnectionManager::get('default');
				$conn->begin();

				// Save Role of User
				if ($user->errors()) {
					$this->Flash->myError('Bạn đã thêm thành viên không thành công!');
				} else {
					if ($this->request->data['userRoles']) {
						foreach ($this->request->data['userRoles'] as $user_role) {

							$roleUser = $this->UserRole->newEntity();
							$usersRole = [];

							$usersRole['user_role_id'] = Text::uuid();
							$usersRole['user_id'] = $user->user_id;
							$usersRole['role_id'] = $user_role;
							$usersRole['userRoles'] = $user_role;

							$roleUser = $this->UserRole->patchEntity($roleUser, $usersRole, ['validate' => 'Roles']);
							if ($roleUser->errors()) {
								$isError = true;
								break;
							}
							$this->UserRole->save($roleUser);
						}
					}
					if ($this->request->data['UserMeta']) {
						foreach ($this->request->data['UserMeta'] as $value) {
							$umeta = $this->UserMeta->newEntity();
							$meta = [];
							$meta['umeta_id'] = Text::uuid();
							$meta['user_id'] = $user->user_id;
							$meta[]['meta_key'] = $value['meta_key'];
							$meta[]['meta_value'] = $value['meta_value'];
							$meta['UserMeta'] = $value;
							$umeta = $this->UserMeta->patchEntity($umeta, $meta);
							if ($umeta->errors('meta_value')) {
								$isError = true;
								break;
							}
							$this->UserMeta->save($umeta);
						}
					}

					if ($isError) {
						$conn->rollback();
					} else {
						$conn->commit();
						if ($this->Users->save($user)) {
							$this->Flash->mySuccess('Bạn đã tạo thành viên thành công!');
							return $this->redirect(['action' => 'index']);
						}
					}
				}
			}
		}
//        }

		$this->set(compact('roleUser'));
		$this->set(compact('user'));
		$this->set(compact('umeta'));
	}

	public function edit($id) {

		$user = $this->Users->find()->where(['Users.delete_status' => 0, 'Users.user_id' => $id])->first();
		$id_user = $user->user_id;
		if (!$user) {
			$this->Flash->myError('Thành viên không tồn tại');
			return $this->redirect(
				array(
					'controller' => 'Users',
					'action' => 'index',
				)
			);
		}

		$this->loadModel('Options');
		$opt = $this->Options->find('list', [
			'keyField' => 'opt_key',
			'valueField' => 'opt_value',
			'order' => 'opt_key'])->toArray();
		foreach ($opt as $key => $value) {
			$meta_key[] = $key;
		}
		$this->set('opt', $opt);

		$datas = [];
		//Get data roles
		$this->loadModel('UserRole');
		$this->loadModel('Roles');
		$this->loadModel('UserMeta');

		$user_role = $this->UserRole->find()
			->select(['role_id', 'user_role_id'])
			->where(['user_id' => $id_user]);

		$datas['userRoles'] = [];
		$datas['Roles'] = [];
		$datas['UserMeta'] = [];
		//Get Roles
		if ($user_role->toArray()) {
			foreach ($user_role->toArray() as $user_roles) {
				$datas['userRoles'][$user_roles->role_id] = $this->getRole($user_roles->role_id);
			}
		}
		//Get Not Roles
		$query = $this->Roles->find()->select(['role_id', 'value']);
		$value = [];
		foreach ($query->toArray() as $user_roles) {
			$roles[] = $user_roles->role_id;
			$value[] = $user_roles->value;
		}
		$role_value = array_combine($roles, $value);

		$datas['Roles'] = array_diff($role_value, $datas['userRoles']);

		//Check meta_key && Get data option
		$arrUmeta = $this->UserMeta->find('all', ['order' => 'meta_key',
			'field' => 'meta_key'])
			->where(['user_id' => $id_user])->toArray();

		$key_meta = [];
		if ($arrUmeta) {
			foreach ($arrUmeta as $key => $arrUserMeta) {
				$datas['UserMeta'][$key][$arrUserMeta->meta_key] = $this->getUmeta($arrUserMeta->meta_key);
				$key_meta[] = $arrUserMeta->meta_key;
			}
		}

		// so sánh bảng options với UserMeta
		$uMeta = array_diff($meta_key, $key_meta);
		$this->set('uMeta', $uMeta);

		//Update data
		if ($this->request->is('post', 'put')) {
			if (!empty($this->request->data)) {
				$user = $this->Users->find()->where(['user_id' => $id_user])->first();
				if (isset($this->request->data['date_of_birth']) && !empty($this->request->data['date_of_birth'])) {
					$this->request->data['date_of_birth'] = date('Y-m-d', strtotime($this->request->data['date_of_birth']));
				}

				if (isset($this->request->data['trial_date']) && !empty($this->request->data['trial_date'])) {
					$this->request->data['trial_date'] = date('Y-m-d', strtotime($this->request->data['trial_date']));
				}
				if (isset($this->request->data['official_date']) && !empty($this->request->data['official_date'])) {
					$this->request->data['official_date'] = date('Y-m-d', strtotime($this->request->data['official_date']));
				}

				$user = $this->Users->patchEntity($user, [
					'personal_email' => $this->request->data['personal_email'],
					'company_email' => $this->request->data['company_email'],
					'nickname' => $this->request->data['nickname'],
					'fullname' => $this->request->data['fullname'],
					'employee_code' => $this->request->data['employee_code'],
					'slogan' => $this->request->data['slogan'],
					'gender' => $this->request->data['gender'],
					'identity_no' => $this->request->data['identity_no'],
					'date_of_birth' => $this->request->data['date_of_birth'],
					'address' => $this->request->data['address'],
					'trial_date' => $this->request->data['trial_date'],
					'official_date' => $this->request->data['official_date'],
				], ['validate' => 'Add']);

				$isError = false;
				$conn = ConnectionManager::get('default');
				$conn->begin();

				// Delete old record
				$this->UserRole->deleteAll([
					'user_id' => $id_user,
				], $cascade = false
				);
				$this->UserMeta->deleteAll([
					'user_id' => $id_user,
				], $cascade = false
				);

				// Save Role of User
				if ($user->errors()) {
					$this->Flash->myError('Bạn đã sửa thành viên không thành công!');
				} else {
					if ($this->request->data['userRoles']) {
						foreach ($this->request->data['userRoles'] as $user_role) {
							$roleUser = $this->UserRole->newEntity();
							$usersRole = [];

							$usersRole['user_role_id'] = Text::uuid();
							$usersRole['user_id'] = $user->user_id;
							$usersRole['role_id'] = $user_role;
							$usersRole['userRoles'] = $user_role;

							$roleUser = $this->UserRole->patchEntity($roleUser, $usersRole, ['validate' => 'Roles']);
							if ($roleUser->errors()) {
								$isError = true;
								break;
							}
							$this->UserRole->save($roleUser);
						}
					}

					if ($this->request->data['UserMeta']) {
						foreach ($this->request->data['UserMeta'] as $value) {
							$umeta = $this->UserMeta->newEntity();
							$meta = [];
							$meta['umeta_id'] = Text::uuid();
							$meta['user_id'] = $user->user_id;
							$meta['meta_key'] = $value['meta_key'];
							$meta['meta_value'] = $value['meta_value'];
							$umeta = $this->UserMeta->patchEntity($umeta, $meta);
							if ($umeta->errors('meta_value')) {
								$isError = true;
								break;
							}
							$this->UserMeta->save($umeta);
						}
					}
					if ($isError) {
						$conn->rollback();
					} else {
						$conn->commit();
						if ($this->Users->save($user)) {
							$this->Flash->mySuccess('Bạn đã tạo thành viên thành công!');
							return $this->redirect(['action' => 'index']);
						}
					}
				}
			}
		}
		$this->set(compact('roleUser'));
		$this->set(compact('user'));
		$this->set(compact('datas'));
	}

	public function delete($id) {

		$users = $this->Users->find()->where([
			'user_id' => $id,
			'delete_status' => 0,
		])->first();
		if (empty($users)) {
			$this->Flash->myError('Thành viên không tồn tại');
			return $this->redirect(['controller' => 'Users', 'action' => 'index']);
		} else {
			$user = $this->Users->patchEntity($users, [
				'delete_status' => '1']);
			$this->Users->save($user);
			$this->Flash->mySuccess("Thành viên đã được xóa.");
			return $this->redirect(['action' => 'index']);
		}
	}

	public function getRole($id = null) {
		$this->loadModel('Roles');
		$query = $this->Roles->find()
			->select(['Roles.value'])
			->where(['role_id' => $id])->first();
		return $query->value;
	}

	public function getUmeta($key = null) {
		$this->loadModel('UserMeta');
		$query = $this->UserMeta->find()
			->select(['UserMeta.meta_value'])
			->where(['meta_key' => $key])->first();
		return $query->meta_value;
	}
	public function profile($id = null) {
		$user_info = $this->Users->find()->where(['Users.delete_status' => 0, 'Users.user_id' => $id])->first();
		$this->set(compact('user_info'));

	}
	public function changeInfomation($id = null) {

	}
	public function changePassword($id = null) {
		$user = $this->Auth->User('user_id');
		if (!empty($this->request->data)) {
			$user = $this->Users->patchEntity($user, [
				'old_password' => $this->request->data['old_password'],
				'password' => $this->request->data['password'],
			],
				['validate' => 'password']);
			if ($this->Users->save($user)) {
				$this->Flash->success('The password is successfully changed');
				$this->redirect(['action' => 'profile']);
			} else {
				$this->Flash->error('There was an error during the save!');
			}
		}
		$this->set('user', $user);
	}
}
