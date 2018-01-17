<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends Table {

	public function initialize(array $config) {
		$this->setTable('user_tbl');

		$this->addBehavior('Timestamp');

		$this->setTable('user_tbl');
		$this->hasMany('ManageOvertimes', array(
			'foreignKey' => 'user_id',
			'bindingKey' => 'user_id',
			'className' => 'ManageOvertimes',
		)
		);

		$this->hasMany('UserMeta', [
			'foreignKey' => 'umeta_id',
			'bindingKey' => 'umeta_id',
			'className' => 'UserMeta',
		]);

		$this->belongsToMany('Roles', [
			'targetForeignKey' => 'role_id',
			'joinTable' => 'user_role_tbl',
			'foreignKey' => 'user_id',
		]);
		$this->belongsTo('role_mst', [
			'foreignKey' => 'role_id',
			'className' => 'Role_mst',
			'joinType' => 'INNER',
		]);

		$this->belongsToMany('Projects', [
			'targetForeignKey' => 'project_id',
			'joinTable' => 'member_project_tbl',
			'foreignKey' => 'project_id',
		]);
		$this->hasMany('Members')
			->setForeignKey('user_id')
			->setDependent(true);
		$this->hasMany('Overtimes')
			->setForeignKey('user_id')
			->setDependent(true);
	}

	public function getNameUser($user_id) {
		$query = $this->find('all')->select([
			'user_id',
			'team_id',
			'username',
			'fullname',
			'nickname',
			'role_id',
		])->WHERE(['user_id IN' => $user_id]);
		return $query;
	}

	public function validationAdd(Validator $validator) {
		return $validator
			->notEmpty('username', 'Tên đăng nhập không được rỗng')
			->notEmpty('password', 'Password không được rỗng')
			->requirePresence('password', 'create')
			->notEmpty('personal_email', 'Email không được rỗng')
			->notEmpty('fullname', 'Tên người dùng không được rỗng')
			->notEmpty('address', 'Địa chỉ của bạn không được để trống')
			->notEmpty('identity_no', 'CMND của bạn không được để trống')

			->add('old_password', 'custom', [
				'rule' => function ($value, $context) {
					$user = $this->get($context['data']['user_id']);
					if ($user) {
						if ((new DefaultPasswordHasher)->check($value, $user->password)) {
							return true;
						}
					}
					return false;
				},
				'message' => 'The old password does not match the current password!',
			])
			->add('password', [
				'length' => [
					'rule' => ['minLength', 6],
					'message' => 'The password have to be at least 6 characters!',
				],
			]);

	}

}
