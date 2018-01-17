<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Search\Manager;
use Cake\ORM\TableRegistry;

class ManageOvertimesTable extends Table {

    public function initialize(array $config) {
        $this->addBehavior('Timestamp', ['events' => [
                'Model.beforeSave' => [
                    'create_time' => 'new',
                    'update_time' => 'always'
                ]
            ]
        ]);
        $this->setTable('manage_overtime_tbl');
        $this->belongsTo('Users', [
            'className' => 'Users',
            'foreignKey' => ['user_id'],
        ]);
    }

    public function getProject($user_id) {

        $query = $this->find('all')->SELECT(['project_id', 'day_of_overtime'])
                ->WHERE(['user_id ' => $user_id, 'delete_status' => 0]);
        $info = $query->toArray();
        $project_id_arr = [];
        foreach ($info as $v) {
            $project_id_arr[] = $v->project_id;
        }

        $this->Projects = TableRegistry::get('Projects');

        $project_name = [];

        foreach ($project_id_arr as $key => $value) {

            $query_getproject = $this->Projects->find()
                    ->select(['name'])
                    ->where(['Projects.project_id' => $value]);
            if (is_object($query_getproject)) {
                $info = $query_getproject->toArray();
            }

            if (!empty($info)) {
                foreach ($info as $k => $v) {
                    $project_name[] = $v->name;
                }
            }
        }
        return $project_name;
    }

    public function getStartAt($user_id) {
        $query = $this->find('all')->select(['start_at'])
                ->where(['user_id ' => $user_id, 'delete_status' => 0]);
        $info = $query->toArray();

        $arrStartAt = [];

        foreach ($info as $key => $value) {
            $arrStartAt[] = $value->start_at;
        }

        return $arrStartAt;
    }

    public function getEndAt($user_id) {
        $query = $this->find('all')->select(['end_at'])
                ->where(['user_id ' => $user_id, 'delete_status' => 0]);
        $info = $query->toArray();

        $arrEndAt = [];

        foreach ($info as $key => $value) {
            $arrEndAt[] = $value->end_at;
        }

        return $arrEndAt;
    }

    public function getDayOfOvertime($user_id) {
        $query = $this->find('all')->where(['user_id ' => $user_id, 'delete_status' => 0]);
        $info = $query->toArray();
        $day_arr = [];
        foreach ($info as $key => $value) {
            $day_arr[] = Date('Y-m-d', strtotime($value->day_of_overtime));
        }

        return $day_arr;
    }

    public function getReason($user_id) {
        $query = $this->find()->select(['reason'])
                ->where(['user_id' => $user_id, 'delete_status' => 0]);
        $info = $query->toArray();

        //Replace Special Character

        $start = ["\r", "\n", ". . ", "\\"];
        $replace = ['. ', '. ', '. ', "\\\\"];

        $arrReason = [];

        foreach ($info as $key => $v) {
            $arrReason[] = str_replace($start, $replace, $v->reason);
        }
        return $arrReason;
    }

    public function getDenyReason($user_id) {
        $query = $this->find()->select(['deny_reason'])
                ->where(['user_id' => $user_id, 'delete_status' => 0]);
        $info = $query->toArray();

        //Replace Special Character

        $start = ["\r", "\n", ". . ", "\\"];
        $replace = ['. ', '. ', '. ', "\\\\"];

        $arrReason = [];

        foreach ($info as $key => $v) {
            $arrReason[] = str_replace($start, $replace, $v->deny_reason);
        }
        return $arrReason;
    }

    //kiem tra co duoc phe duyet chua
    public function testApproveStatus($user_id) {
        $query = $this->find()->select(['approve_status'])
                ->where(['user_id' => $user_id, 'delete_status' => 0]);
        $info = $query->toArray();
        $approve_stt_arr = [];
        foreach ($info as $key => $v) {
            $approve_stt_arr[] = $v->approve_status;
        }
        return $approve_stt_arr;
    }

    //lay ten nguoi duyet project
    public function getApprover($user_id) {
        $query = $this->find()->select(
                        array(
                            'ManageOvertimes.user_id',
                            'ManageOvertimes.approver'
                        )
                )
                ->where(['ManageOvertimes.user_id' => $user_id, 'delete_status' => 0]);

        $user_id = array();
        foreach ($query->toArray() as $key => $value) {
            if ($value['approver'] != null) {
                $user_id[$key] = $value['approver'];
            }
        }

        $this->Users = TableRegistry::get('Users');
        $approver = [];

        if (empty($user_id)) {

            return $approver;
        } else {
            foreach ($user_id as $key => $value) {
                if ($value != '') {
                    $query_getname = $this->Users->find()
                            ->select(['fullname'])
                            ->where(['Users.user_id' => $value]);
                }

                if (is_object($query_getname)) {
                    $info = $query_getname->toArray();
                }
                if (!empty($info)) {
                    foreach ($info as $k => $v) {
                        $approver[] = $v->fullname;
                    }
                }
            }
            return $approver;
        }
    }

    public function getNameUser($user_id) {
        $query = $this->find()->select(
                        array(
                            'ManageOvertimes.user_id'
                        )
                )
                ->where(['ManageOvertimes.user_id' => $user_id, 'delete_status' => 0]);

        $user_id = array();
        foreach ($query->toArray() as $key => $value) {
            $user_id[$key] = $value['user_id'];
        }
        $this->Users = TableRegistry::get('Users');
        $name = [];
        foreach ($user_id as $key => $value) {
            $query_getname = $this->Users->find()
                    ->select(['username'])
                    ->where(['Users.user_id' => $value]);

            foreach ($query_getname->toArray() as $key => $value) {
                $name[$key] = $value['username'];
            }
            // pr($query_getname->toArray());
        }
        return $name;
    }

    //task 4
    public function getProjectArr($user_id) {

        $this->MemberProjects = TableRegistry::get('MemberProjects');

        $query = $this->MemberProjects->find('all')->SELECT(['project_id'])
                ->WHERE(['user_id ' => $user_id]);
        $info = $query->toArray();

        $project_id_arr = [];
        foreach ($info as $v) {
            $project_id_arr[] = $v->project_id;
        }
        $this->Projects = TableRegistry::get('Projects');
        $query_getproject = [];
        $arr = [];
        foreach ($project_id_arr as $key => $value) {
            $query_getproject = $this->Projects->find()
                    ->select(['project_id', 'name'])
                    ->where(['Projects.project_id' => $value])
                    ->andwhere(['Projects.status' => 2]);
            foreach ($query_getproject->toArray() as $row) {
                $arr[$row['project_id']] = $row['name'];
            }
        }
        return $arr;
    }

    //task 5
    public function getProjectIsLeader($user_id) {
        $this->MemberProjects = TableRegistry::get('MemberProjects');
        $query = $this->MemberProjects->find('all')->SELECT(['project_id'])
                ->WHERE(['user_id ' => $user_id])
                ->ANDWHERE(['is_leader' => 1]);
        $info = $query->toArray();
        $project_id_arr = [];
        foreach ($info as $v) {
            $project_id_arr[] = $v->project_id;
        }
        $this->Projects = TableRegistry::get('Projects');
        $query_getproject = [];
        $arr = [];
        foreach ($project_id_arr as $key => $value) {
            $query_getproject = $this->Projects->find()
                    ->select(['project_id', 'name'])
                    ->where(['Projects.project_id' => $value])
                    ->andwhere(['Projects.status' => 2]);



            foreach ($query_getproject->toArray() as $row) {
                $arr[$row['project_id']] = $row['name'];
            }
        }

        return $arr;
    }

    public function getIdProjectLeader($user_id) {
        $this->MemberProjects = TableRegistry::get('MemberProjects');
        $query = $this->MemberProjects->find('all')->SELECT(['project_id'])
                ->join([
                    'table' => 'project_tbl',
                    'alias' => 'Projects',
                    'type' => 'INNER',
                    'conditions' => [
                        'Projects.project_id = MemberProjects.project_id',
                    ],
                ])
                ->WHERE(['MemberProjects.user_id ' => $user_id,
            'MemberProjects.is_leader' => 1,
            'Projects.status' => 2]);
        $info = $query->toArray();
        $project_id_arr = [];
        foreach ($info as $v) {

            $project_id_arr[] = $v->project_id;
        }

        return $project_id_arr;
    }

    public function getMemberProjectLeader($project_id) {
        $this->MemberProjects = TableRegistry::get('MemberProjects');
        $query = $this->MemberProjects->find('all')
                ->WHERE(['project_id IN' => $project_id]);
        $info = $query->toArray();

        $user_id_arr = [];
        $name_arr = [];
        foreach ($info as $v) {
            $user_id_arr[] = $v->user_id;
        }

        $this->Users = TableRegistry::get('Users');
        foreach ($user_id_arr as $key => $value) {
            $query_getusername = $this->Users->find()
                    ->select(['user_id', 'fullname'])
                    ->where(['Users.user_id' => $value]);
            foreach ($query_getusername->toArray() as $row) {
                $name_arr[$row['user_id']] = $row['fullname'];
            }
        }
        return $name_arr;
    }

    public function validationAdd(Validator $validator) {

        $project = TableRegistry::get('Projects');
        $query_project = $project->find()->select(['project_id']);
        $arrProjectId = [];
        foreach ($query_project as $row) {
            array_push($arrProjectId, $row->project_id);
        }


        $user = TableRegistry::get('Users');
        $query_user = $user->find()->select(['user_id']);
        $arrUserId = [];
        foreach ($query_user as $row) {
            array_push($arrUserId, $row->user_id);
        }

        return $validator
                        ->notEmpty('day_overtime', 'Hãy nhập ngày làm thêm giờ')
                        ->notEmpty('reason', 'Hãy nhập lí do')
                        ->notEmpty('project_id', 'Hãy nhập dự án')
                        ->notEmpty('start_at', 'Hãy nhập thời gian bắt đầu')
                        ->notEmpty('end_at', 'Hãy nhập thời gian kết thúc')
                        ->add('project_id', 'inList', [
                            'rule' => ['inList', $arrProjectId],
                            'message' => 'Sai dự án'
                        ])
                        ->add('user_id', 'inList', [
                            'rule' => ['inList', $arrUserId],
                            'message' => 'Sai thành viên'
                        ])
                        ->add('start_at', 'custom', [
                            'rule' => function ($value, $context) {
                                $query = $this->find()->where([
                                    'user_id' => $context['data']['user_id'],
                                    'day_of_overtime' => date("Y-m-d", strtotime($context['data']['day_overtime'])),
                                    'start_at <=' => $context['data']['end_at'],
                                    'end_at >=' => $context['data']['start_at'],
                                    'delete_status' => 0
                                ]);
                                return (!$query->toArray());
                            }, 'message' => 'Đơn làm thêm bị trùng thời gian'
                        ])
                        ->add('end_at', 'custom', [
                            'rule' => function ($value, $context) {
                                $query = $this->find()->where([
                                    'user_id' => $context['data']['user_id'],
                                    'day_of_overtime' => date("Y-m-d", strtotime($context['data']['day_overtime'])),
                                    'start_at <=' => $context['data']['end_at'],
                                    'end_at >=' => $context['data']['start_at'],
                                    'delete_status' => 0,
                                ]);
                                return (!$query->toArray());
                            }, 'message' => 'Đơn làm thêm bị trùng thời gian'
                        ])
                        ->add('day_overtime', 'custom', [
                            'rule' => function ($value, $context) {
                                $project = TableRegistry::get('Projects');
                                $query = $project->find()->select(['start_date', 'end_date'])

                                        ->where(['project_id' => $context['data']['project_id']])
                                        ->first();
                                $day_of_overtime = strtotime($context['data']['day_of_overtime']);

                                $start_date = strtotime($query->start_date);

                                $end_date = strtotime($query->end_date);

                                if ($end_date == false) {
                                    return ($day_of_overtime >= $start_date);
                                } else {
                                    return ($day_of_overtime >= $start_date && $day_of_overtime <= $end_date);
                                }
                            }, 'message' => 'Ngày làm thêm không nằm trong ngày thực hiện dự án'
                        ])
                        ->add('user_id', 'custom', [
                            'rule' => function ($value, $context) {
                                $memberProject = TableRegistry::get('MemberProject');
                                $query = $memberProject->find()->where([
                                    'project_id' => $context['data']['project_id'],
                                    'user_id' => $context['data']['user_id']
                                ]);
                                return (!empty($query->toArray()));
                            }, 'message' => 'Thành viên không thuộc dự án']);
    }

}
