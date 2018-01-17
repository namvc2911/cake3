<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;

/**
 *
 */
class GroupsController extends AppController {
	public $paginate = array('limit' => 1);

	public function initialize() {
		parent::initialize();
		$this->viewBuilder()->setLayout('my_layout');
		$this->loadComponent('Paginator');
	}
	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);
		$this->Auth->allow(['index', 'add', 'listGroup']);
	}
	public function add() {
		$userTable = TableRegistry::get('user_tbl');
		$userGroupTable = TableRegistry::get('user_group_tbl');
		$list_user = $userTable->find()
			->select(['user_tbl.fullname'])

			->order(['fullname' => 'ASC'])->hydrate(false)->toArray();
		$this->set(compact('list_user'));
		if ($this->request->is('post')) {
			$get = $this->request->getData();
			if (count($get['leader']) <= 1) {
				//save to group_tbl
				$groupTable = TableRegistry::get('group_tbl');
				$group = $groupTable->newEntity();
				$group->group_id = Text::uuid();
				$group->name = $get['name'];
				$group->description = $get['description'];
				$group->slogan = $get['slogan'];

				$groupTable->save($group);

				//save members
				$user_groupTable = TableRegistry::get('user_group_tbl');
				for ($i = 0; $i < count($get['members']); $i++) {
					$group_member = $user_groupTable->newEntity();
					$group_member->user_group_id = Text::uuid();
					$group_member->user_id = $get['members'][$i];
					$group_member->group_id = $group->group_id;
					$group_member->is_leader = 0;
					$user_groupTable->save($group_member);
				}
				for ($j = 0; $j < count($get['leader']); $j++) {
					$group_leader = $user_groupTable->newEntity();
					$group_leader->user_group_id = Text::uuid();
					$group_leader->user_id = $get['leader'][$j];
					$group_leader->group_id = $group->group_id;
					$group_leader->is_leader = 1;
					$user_groupTable->save($group_leader);
				}
				for ($k = 0; $k < count($get['sub_leader']); $k++) {
					$group_sub_leader = $user_groupTable->newEntity();
					$group_sub_leader->user_group_id = Text::uuid();
					$group_sub_leader->user_id = $get['sub_leader'][$k];
					$group_sub_leader->group_id = $group->group_id;
					$group_sub_leader->is_leader = 2;
					$user_groupTable->save($group_sub_leader);
				}
				$this->redirect(['controller' => 'Groups', 'action' => 'listGroup']);
			}
			$this->Flash->error(__('Chỉ được chọn 1 leader'));
		}
		$this->set(compact('userGroupTable'));

	}
	public function listGroup() {

		$sub_leader1 = array();
		$listGroupTable = TableRegistry::get('group_tbl');
		$userGroupTable = TableRegistry::get('user_group_tbl');
		$group_leader = $listGroupTable->find('all')
			->select(['group_tbl.name', 'group_tbl.group_id', 'UsersTable1.fullname', 'group_tbl.slogan'])
			->join([
				array(
					'table' => 'user_group_tbl',
					'type' => 'LEFT',
					'alias' => 'UserGroupsTable1',
					'conditions' => [
						'UserGroupsTable1.is_leader' => 1,
						'UserGroupsTable1.group_id = group_tbl.group_id',
					],
				),
				array(
					'table' => 'user_tbl',
					'type' => 'LEFT',
					'alias' => 'UsersTable1',
					'conditions' => 'UsersTable1.user_id = UserGroupsTable1.user_id',
				),
			])
			->hydrate(false)
			->toArray();

		for ($i = 0; $i < count($group_leader); $i++) {
			$sub_leader = $listGroupTable->find('all')
				->select(['UsersTable2.fullname'])
				->join([
					array(
						'table' => 'user_group_tbl',
						'type' => 'LEFT',
						'alias' => 'UserGroupsTable2',
						'conditions' => [
							'UserGroupsTable2.is_leader' => 2,
							'UserGroupsTable2.group_id = group_tbl.group_id',
						],
					),
					array(
						'table' => 'user_tbl',
						'type' => 'LEFT',
						'alias' => 'UsersTable2',
						'conditions' => 'UsersTable2.user_id = UserGroupsTable2.user_id',
					),
				])
				->where(['UserGroupsTable2.group_id' => $group_leader[$i]['group_id']])
				->toArray();

			if (!empty($sub_leader)) {
				$sub_leader1 = [];
				for ($j = 0; $j < count($sub_leader); $j++) {
					$sub_leader1[] = $sub_leader[$j]['UsersTable2']['fullname'];
				}
				$group_leader[$i]['sub_leader'] = implode(", ", $sub_leader1);
			} else {
				$group_leader[$i]['sub_leader'] = '';
			}
		}
		$this->paginate = array('limit' => 2);
		$this->set('list', $group_leader);
		$this->set('list', $this->paginate());
		$this->set(compact('group_leader'));

	}
	public function index() {

	}

}