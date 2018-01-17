<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Utility\Text;

class ProjectsController extends AppController {

	public $paginate = [
		'limit' => 10,
	];

	public function initialize() {
		parent::initialize();
		$this->viewBuilder()->setLayout('my_layout');
		$this->loadComponent('Paginator');
	}

	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);
	}

	// List Project
	public function listProject() {
		if ($this->Auth->user()['role'] === 'MEMBERS') {
			return $this->redirect($this->Auth->redirectUrl());
		}
		$data = $this->request->query();
		$conditions = [];
		if ($this->request->is(['get'])) {

			$searchNameProject = isset($data['np']) ? $data['np'] : '';
			$searchStatusProject = isset($data['s']) ? $data['s'] : '';
			$searchDayStart = isset($data['ds']) ? $data['ds'] : '';
			$searchDayEnd = isset($data['de']) ? $data['de'] : '';

			if (isset($searchNameProject) && $searchNameProject) {
				$conditions['name LIKE'] = '%' . $searchNameProject . '%';
			}

			if (isset($searchStatusProject) && $searchStatusProject) {
				if ($searchStatusProject != -1) {

					$conditions['status'] = $searchStatusProject;
				}
			}
			if ((isset($searchDayStart) && !empty($searchDayStart)) && (!isset($searchDayEnd) || empty($searchDayEnd))) {
				$conditions['OR'] = array(
					array(
						'end_date >=' => date('Y-m-d H:i:s', strtotime($searchDayStart)),
					),
					array(
						'end_date IS NULL',
					),
				);
			} elseif ((!isset($searchDayStart) || empty($searchDayStart)) && (isset($searchDayEnd) && !empty($searchDayEnd))) {
				$conditions['start_date <='] = date('Y-m-d H:i:s', strtotime($searchDayEnd));
			} elseif ((isset($searchDayStart) && !empty($searchDayStart)) && (isset($searchDayEnd) && !empty($searchDayEnd))) {
				$conditions['OR'] = array(
					array(
						'end_date >=' => date('Y-m-d H:i:s', strtotime($searchDayStart)),
						'start_date <=' => date('Y-m-d H:i:s', strtotime($searchDayEnd)),
					),
					array(
						'start_date <=' => date('Y-m-d H:i:s', strtotime($searchDayEnd)),
						'end_date IS NULL',
					),
				);
			}
		}
		$projects = $this->Projects->find('all', ['order' => ['Projects.name' => 'ASC']])
			->where($conditions)
			->contain(['Users' => function ($q) {
				return $q->select(['Users.fullname'])
					->where(['is_leader' => 1]);
			},
			]);

		try {
			$this->paginate($projects);
		} catch (NotFoundException $ex) {
			return $this->redirect(['action' => 'listProject']);
		}
		$this->set(compact('projects', 'data'));
	}

	// View detail Project
	public function viewProject($id = null) {
		if ($this->Auth->user()['role'] === 'MEMBERS') {
			return $this->redirect($this->Auth->redirectUrl());
		}
		$project = $this->Projects->find()->where(['project_id' => $id])->first();
		if (!$project) {
			$this->Flash->myError('Dự án không tồn tại');

			return $this->redirect(
				array(
					'controller' => 'Projects',
					'action' => 'listProject',
				)
			);
		}

		$this->loadModel('MemberProject');
		$query = $this->MemberProject->find()
			->where(['MemberProject.project_id' => $project->project_id])
			->hydrate(false)
			->contain(['Users' => function ($q) {
				return $q->select(['Users.fullname']);
			},
			]);

		$this->set(compact('query'));
		$this->set(compact('project'));
	}

	// Delete Project
	public function deleteProject($id = null) {
		if ($this->Auth->user()['role'] === 'MEMBERS') {
			return $this->redirect($this->Auth->redirectUrl());
		}

		$project = $this->Projects->find()->where(['project_id' => $id, 'status !=' => 0])->first();

		if (!$project) {
			$this->Flash->myError('Dự án không tồn tại!');
			return $this->redirect(
				array(
					'controller' => 'Projects',
					'action' => 'listProject',
				)
			);
		} else {
			$project = $this->Projects->patchEntity($project, ['status' => '0']);
			$this->Projects->save($project);
			$this->Flash->mySuccess("Dự án đã được xóa.");
			return $this->redirect(['action' => 'listProject']);
		}
	}

	public function add() {
		if ($this->Auth->user()['role'] === 'MEMBERS') {
			return $this->redirect($this->Auth->redirectUrl());
		}
		$this->loadModel('MemberProject');
		$member_project = $this->MemberProject->newEntity();
		$project = $this->Projects->newEntity();
		$this->loadModel('Users');
		$listuser = $this->Users->find('list', [
			'keyField' => 'user_id',
			'valueField' => 'fullname',
			'order' => ['fullname' => 'ASC'],
		])->toArray();
		$this->set('listuser', $listuser);

		if ($this->request->is('post', 'put')) {
			if (!empty($this->request->data)) {
				if (isset($this->request->data['start_date']) && !empty($this->request->data['start_date'])) {
					$this->request->data['start_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['start_date']));
				}
				if (isset($this->request->data['end_date']) && !empty($this->request->data['end_date'])) {
					$this->request->data['end_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['end_date']));
				}
				$project = $this->Projects->patchEntity($project, [
					'project_id' => Text::uuid(),
					'name' => $this->request->data['name'],
					'description' => $this->request->data['description'],
					'start_date' => $this->request->data['start_date'],
					'end_date' => $this->request->data['end_date'],
					'status' => $this->request->data['status'],
				], ['validate' => 'Add']);

				// Save Info of Member
				$isError = false;
				$conn = ConnectionManager::get('default');
				$conn->begin();

				if ($project->errors()) {
					$this->Flash->myError('Tạo dự án không thành công');
				} else {

					// Save Info of Member
					if ($this->request->data['members']) {
						foreach ($this->request->data['members'] as $member) {

							$member_project = $this->MemberProject->newEntity();
							$memberData = [];

							$memberData['member_project_id'] = Text::uuid();
							$memberData['project_id'] = $project->project_id;
							$memberData['user_id'] = $member;
							$memberData['is_leader'] = 0;
							$memberData['members'] = $member;
							$member_project = $this->MemberProject->patchEntity($member_project, $memberData, ['validate' => 'Member']);
							if ($member_project->errors()) {
								$isError = true;
								break;
							}
							$this->MemberProject->save($member_project);
						}
					}
					// Save Info of Leader
					if ($this->request->data['leader'] && $isError == false) {
						foreach ($this->request->data['leader'] as $member) {
							$member_project = $this->MemberProject->newEntity();
							$memberData = [];

							$memberData['count_leader'] = count($this->request->data['leader']);
							$memberData['member_project_id'] = Text::uuid();
							$memberData['project_id'] = $project->project_id;
							$memberData['user_id'] = $member;
							$memberData['is_leader'] = 1;
							$memberData['leader'] = $member;
							$member_project = $this->MemberProject->patchEntity($member_project, $memberData, ['validate' => 'Member']);
							if ($member_project->errors()) {
								$isError = true;
								break;
							}
							$this->MemberProject->save($member_project);
						}
					}
					if ($isError) {
						$conn->rollback();
					} else {
						$conn->commit();
						if ($this->Projects->save($project)) {
							$this->Flash->mySuccess('Thêm dự án thành công');
							return $this->redirect(['controller' => 'Projects', 'action' => 'listProject']);
						}
					}
				}
			}
		}
		$this->set(compact('member_project'));
		$this->set(compact('project'));
	}

	public function edit($id = null) {
		if ($this->Auth->user()['role'] === 'MEMBERS') {
			return $this->redirect($this->Auth->redirectUrl());
		}
		$project = $this->Projects->find()->where(['project_id' => $id, 'status !=' => 0])->first();

		$id_project = $project->project_id;
		if (!$project) {
			$this->Flash->myError('Dự án không tồn tại');
			return $this->redirect(
				array(
					'controller' => 'Projects',
					'action' => 'listProject',
				)
			);
		}
		$datas = [];
		//Get old data from project table
		$datas['project']['name'] = $project->name;
		$datas['project']['description'] = $project->description;
		$datas['project']['start_date'] = date("Y-m-d", strtotime($project->start_date));
		$datas['project']['end_date'] = isset($project->end_date) ? $project->end_date->format('Y-m-d') : null;
		$datas['project']['status'] = $project->status;

		$this->loadModel('MemberProject');
		$this->loadModel('Users');
		//Get data member joined and leader
		$member_project = $this->MemberProject->find()
			->select(['user_id', 'is_leader'])
			->where(['project_id' => $id_project]);
		$datas['leader'] = [];
		$datas['member'] = [];
		$datas['users'] = [];
		if ($member_project->toArray()) {
			foreach ($member_project->toArray() as $user) {
				if ($user->is_leader == 1) {
					$datas['leader'][$user->user_id] = $this->getNameUser($user->user_id);
				} else {
					$datas['member'][$user->user_id] = $this->getNameUser($user->user_id);
				}
			}
		}
		//Get user not joined
		$query = $this->Users->find('all', ['order' => ['fullname' => 'ASC']])->select(['user_id', 'fullname']);
		foreach ($query->toArray() as $user) {
			if (!array_key_exists($user->user_id, $datas['leader']) && !array_key_exists($user->user_id, $datas['member'])) {
				$datas['users'][$user->user_id] = $user->fullname;
			}
		}
		//Update data
		if ($this->request->is('post', 'put')) {
			if (!empty($this->request->data)) {
				$project = $this->Projects->find()->where(['project_id' => $id_project])->first();
				if (isset($this->request->data['start_date']) && !empty($this->request->data['start_date'])) {
					$this->request->data['start_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['start_date']));
				}
				if (isset($this->request->data['end_date']) && !empty($this->request->data['end_date'])) {
					$this->request->data['end_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['end_date']));
				}
				$project = $this->Projects->patchEntity($project, [
					'name' => $this->request->data['name'],
					'description' => $this->request->data['description'],
					'start_date' => $this->request->data['start_date'],
					'end_date' => $this->request->data['end_date'],
					'status' => $this->request->data['status'],
				], ['validate' => 'Add']);

				// Save Info of Member
				$isError = false;
				$conn = ConnectionManager::get('default');
				$conn->begin();

				// Delete old record of project
				$this->MemberProject->deleteAll([
					'project_id' => $id_project,
				], $cascade = false
				);
				if ($project->errors()) {
					$this->Flash->myError('Sửa dự án không thành công');
				} else {
					if ($this->request->data['members']) {
						foreach ($this->request->data['members'] as $member) {

							$member_project = $this->MemberProject->newEntity();
							$memberData = [];

							$memberData['member_project_id'] = Text::uuid();
							$memberData['project_id'] = $id_project;
							$memberData['user_id'] = $member;
							$memberData['is_leader'] = 0;
							$memberData['members'] = $member;
							$member_project = $this->MemberProject->patchEntity($member_project, $memberData, ['validate' => 'Member']);
							if ($member_project->errors()) {
								$isError = true;
								break;
							}
							$this->MemberProject->save($member_project);
						}
					}
					// Save Info of Leader
					if ($this->request->data['leader'] && $isError == false) {
						foreach ($this->request->data['leader'] as $member) {
							$member_project = $this->MemberProject->newEntity();
							$memberData = [];

							$memberData['count_leader'] = count($this->request->data['leader']);
							$memberData['member_project_id'] = Text::uuid();
							$memberData['project_id'] = $id_project;
							$memberData['user_id'] = $member;
							$memberData['is_leader'] = 1;
							$memberData['leader'] = $member;
							$member_project = $this->MemberProject->patchEntity($member_project, $memberData, ['validate' => 'Member']);
							if ($member_project->errors()) {
								$isError = true;
								break;
							}
							$this->MemberProject->save($member_project);
						}
					}
					if ($isError) {
						$conn->rollback();
					} else {
						$conn->commit();
						if ($this->Projects->save($project)) {
							$this->Flash->mySuccess('Sửa dự án thành công');
							return $this->redirect(['controller' => 'Projects', 'action' => 'listProject']);
						}
					}
				}
			}
		}
		$this->set(compact('member_project'));
		$this->set(compact('project'));
		$this->set(compact('datas'));
	}

	public function getNameUser($id = null) {
		$this->loadModel('Users');
		$query = $this->Users->find()
			->select(['Users.fullname'])
			->where(['user_id' => $id])->first();
		return $query->fullname;
	}

	public function viewByUserId($id = null) {
		if ($this->Auth->user()['role'] === 'MEMBERS') {
			return $this->redirect($this->Auth->redirectUrl());
		}
		$this->loadModel('Users');
		$user = $this->Users->find()->select(['Users.fullname'])->where(['user_id' => $id])->first();
		$conditions = [];
		$data = $this->request->query();

		if ($this->request->is('get')) {
			$searchNameProject = isset($data['np']) ? $data['np'] : '';
			$searchStatusProject = isset($data['s']) ? $data['s'] : '';
			$searchDayStart = isset($data['ds']) ? $data['ds'] : '';
			$searchDayEnd = isset($data['de']) ? $data['de'] : '';

			if (isset($searchNameProject) && $searchNameProject) {
				$conditions['name LIKE'] = '%' . $searchNameProject . '%';
			}
			if (isset($searchStatusProject) && !empty($searchStatusProject) && $searchStatusProject != -1) {
				$conditions['status'] = $searchStatusProject - 1;
			}
			if ((isset($searchDayStart) && !empty($searchDayStart)) && (!isset($searchDayEnd) || empty($searchDayEnd))) {
				$conditions['OR'] = array(
					array(
						'end_date >=' => date('Y-m-d H:i:s', strtotime($searchDayStart)),
					),
					array(
						'end_date IS NULL',
					),
				);
			} elseif ((!isset($searchDayStart) || empty($searchDayStart)) && (isset($searchDayEnd) && !empty($searchDayEnd))) {
				$conditions['start_date <='] = date('Y-m-d H:i:s', strtotime($searchDayEnd));
			} elseif ((isset($searchDayStart) && !empty($searchDayStart)) && (isset($searchDayEnd) && !empty($searchDayEnd))) {
				$conditions['OR'] = array(
					array(
						'end_date >=' => date('Y-m-d H:i:s', strtotime($searchDayStart)),
						'start_date <=' => date('Y-m-d H:i:s', strtotime($searchDayEnd)),
					),
					array(
						'start_date <=' => date('Y-m-d H:i:s', strtotime($searchDayEnd)),
						'end_date IS NULL',
					),
				);
			}
		}
		$this->loadModel('MemberProject');
		$project = $this->MemberProject->find('all', ['order' => ['Projects.name' => 'ASC']])->where(['user_id' => $id])
			->andWhere($conditions)
			->hydrate(false)
			->contain(['Projects' => function ($q) {
				return $q->contain(['Users' => function ($p) {
					return $p->select(['Users.fullname'])
						->where(['is_leader' => 1]);
				},
				]);
			},
			]);

		try {
			$this->paginate($project);
		} catch (NotFoundException $ex) {
			return $this->redirect(['action' => 'viewByUserId', $id]);
		}
		$this->set('projects', $project);
		$this->set(compact('data'));
		$this->set(compact('user'));
	}

	public function index() {
		$user_id = $this->Auth->user()['user_id'];

		$data = $this->request->query();
		$conditions = [];
		if ($this->request->is('get')) {
			$searchNameProject = isset($data['np']) ? $data['np'] : '';
			$searchStatusProject = isset($data['s']) ? $data['s'] : '';
			$searchDayStart = isset($data['ds']) ? $data['ds'] : '';
			$searchDayEnd = isset($data['de']) ? $data['de'] : '';

			if (isset($searchNameProject) && $searchNameProject) {
				$conditions['name LIKE'] = '%' . $searchNameProject . '%';
			}

			if (isset($searchStatusProject) && $searchStatusProject) {
				if ($searchStatusProject != -1) {

					$conditions['status'] = $searchStatusProject;
				}
				if ($searchStatusProject == 4) {
					$conditions['status'] = 0;
				}
			}
			if ((isset($searchDayStart) && !empty($searchDayStart)) && (!isset($searchDayEnd) || empty($searchDayEnd))) {
				$conditions['OR'] = array(
					array(
						'end_date >=' => date('Y-m-d H:i:s', strtotime($searchDayStart)),
					),
					array(
						'end_date IS NULL',
					),
				);
			} elseif ((!isset($searchDayStart) || empty($searchDayStart)) && (isset($searchDayEnd) && !empty($searchDayEnd))) {
				$conditions['start_date <='] = date('Y-m-d H:i:s', strtotime($searchDayEnd));
			} elseif ((isset($searchDayStart) && !empty($searchDayStart)) && (isset($searchDayEnd) && !empty($searchDayEnd))) {
				$conditions['OR'] = array(
					array(
						'end_date >=' => date('Y-m-d H:i:s', strtotime($searchDayStart)),
						'start_date <=' => date('Y-m-d H:i:s', strtotime($searchDayEnd)),
					),
					array(
						'start_date <=' => date('Y-m-d H:i:s', strtotime($searchDayEnd)),
						'end_date IS NULL',
					),
				);
			}
		}

		$member = $this->loadModel('MemberProjects');
		$project_ids = $member->getdata($user_id);
		$projects = [];
		if (!empty($project_ids)) {
			$project = $this->loadModel('Projects');
			$projects = $project->getData($project_ids, $conditions);
			try {
				$this->paginate($projects);
			} catch (NotFoundException $ex) {
				return $this->redirect(['action' => 'index']);
			}
		}
		$this->set(compact('projects', 'data'));
	}

	public function view($project_id = null) {

		$this->loadModel('Projects');
		$projects = $this->Projects->find()->where(['project_id' => $project_id])->first();
		if (!$projects) {
			$this->Flash->myError('Dự án không tồn tại');

			return $this->redirect(
				array(
					'controller' => 'Projects',
					'action' => 'index',
				)
			);
		}
		$this->set(compact('projects'));

		$member = $this->loadModel('MemberProjects');
		$user_id = $member->getMemberProject($project_id);
		$user = $this->loadModel("Users");
		$users = $user->getNameUser($user_id);

		$this->set(compact('users'));

		$user_id_leader = $member->getLeaderProject($project_id);
		$users1 = [];
		if ($user_id_leader) {
			$users1 = $user->getNameUser($user_id_leader);
		}
		$this->set(compact('users1'));
	}

}

?>
