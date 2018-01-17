<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Network\Exception\NotFoundException;

/**
 * Description of OvertimesController
 *
 * @author manht
 */
class OvertimesController extends AppController {

    public $paginate = [
        'limit' => 10
    ];

    public function initialize() {
        parent::initialize();
        $this->viewBuilder()->setLayout('my_layout');
        $this->loadComponent('Paginator');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
    }

    // List Overtime
    public function index() {
        if ($this->Auth->user()['role'] === 'MEMBERS') {
            return $this->redirect($this->Auth->redirectUrl());
        }
        $this->loadModel('Users');
        $this->loadModel('Projects');
        $listUserMember = $this->Users->find('all')->select(['user_id', 'fullname'])
                ->order(['fullname' => 'ASC']);
        $this->set('users', $listUserMember);

        // Get Years
        $query = $this->Overtimes->find();
        $year = $query->func()->year([
            'day_of_overtime' => 'identifier'
        ]);
        $query->select([
            'yearOvertime' => $year,
        ])->distinct('yearOvertime');
        $arrYear = [];
        foreach ($query as $row) {
            $arrYear[$row->yearOvertime] = $row->yearOvertime;
        }
        // If not find year in query , set default by current year
        if (empty($arrYear))
            $arrYear[Time::now()->year] = Time::now()->year;
        $this->set(compact('arrYear'));
        $this->set('currentMonth', (Time::now()->month));
        $this->set('currentYear', (Time::now()->year));

        $result = [];

        //Load Data of Current Month and Current Year
        $arrDayOfMonthCurrent = [];
        //Get all day of month
        for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, (Time::now()->month), (Time::now()->year)); $i++) {
            $d = new Time(Time::now()->year . '-' . Time::now()->month . '-' . $i);
            array_push($arrDayOfMonthCurrent, $d->format('Y-m-d'));
        }

        //Get all result of month
        foreach ($arrDayOfMonthCurrent as $day) {
            $overtimes = $this->Overtimes->find('all')
                    ->select([
                        'name_project' => 'Projects.name',
                        'Overtimes.reason',
                        'Overtimes.start_at',
                        'Overtimes.end_at',
                        'Overtimes.breaktime',
                        'Overtimes.deny_reason',
                        'Overtimes.approve_status',
                        'name_approver' => 'Users.fullname',
                        'created_by' => 'Users2.fullname'
                    ])->order([
                        'Overtimes.day_of_overtime'
                    ])
                    ->join([
                        'table' => 'user_tbl',
                        'alias' => 'Users',
                        'type' => 'LEFT',
                        'conditions' => 'Overtimes.approver = Users.user_id',
                    ])
                    ->join([
                        'table' => 'user_tbl',
                        'alias' => 'Users2',
                        'type' => 'LEFT',
                        'conditions' => 'Overtimes.created_by = Users2.user_id',
                    ])
                    ->join([
                        'table' => 'project_tbl',
                        'alias' => 'Projects',
                        'type' => 'LEFT',
                        'conditions' => 'Projects.project_id = Overtimes.project_id',
                    ])
                    ->where([
                'Overtimes.day_of_overtime =' => $day,
                'Overtimes.delete_status =' => 0,
            ]);
            // Get name of Project and Approver

            array_push($result, [$day, $overtimes->toArray()]);
        }
        $overtimes = $this->Overtimes->newEntity();

        //When Search
        if ($this->request->is('post')) {

            $result = [];
            $conditions = [];
            $user_id = $this->request->data['user'];
            $month = $this->request->data['month'];
            $year = !empty($this->request->data['year']) ? $this->request->data['year'] : "";
            $approve_status = $this->request->data['approve_status'];
//            pr($user_id);pr($approve_status);die;
            if ($user_id != '-1') {
                $conditions = [
                    'Overtimes.user_id' => $user_id,
                ];
            }

            if ($approve_status != '-1') {
                $conditions = [
                    'Overtimes.approve_status' => $approve_status,
                ];
            }
            $overtimes = $this->Overtimes->patchEntity($overtimes, [
                'month' => $month,
                'year' => $year
                    ], ['validate' => 'Search']);

            //Get all day of month
            $arrDayOfMonth = [];
            if (empty($overtimes->errors())) {
                for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $i++) {
                    $d = new Time($year . '-' . $month . '-' . $i);
                    array_push($arrDayOfMonth, $d->format('Y-m-d'));
                }
            }
            //Get all result of month
            foreach ($arrDayOfMonth as $day) {
                $overtimes = $this->Overtimes->find('all')
                        ->select([
                            'name_project' => 'Projects.name',
                            'Overtimes.reason',
                            'Overtimes.start_at',
                            'Overtimes.end_at',
                            'Overtimes.breaktime',
                            'Overtimes.deny_reason',
                            'Overtimes.approve_status',
                            'created_by' => 'Users2.fullname',
                            'name_approver' => 'Users.fullname'
                        ])
                        ->join([
                            'table' => 'user_tbl',
                            'alias' => 'Users',
                            'type' => 'LEFT',
                            'conditions' => 'Overtimes.approver = Users.user_id',
                        ])
                        ->join([
                            'table' => 'user_tbl',
                            'alias' => 'Users2',
                            'type' => 'LEFT',
                            'conditions' => 'Overtimes.created_by = Users2.user_id',
                        ])
                        ->join([
                            'table' => 'project_tbl',
                            'alias' => 'Projects',
                            'type' => 'LEFT',
                            'conditions' => 'Projects.project_id = Overtimes.project_id',
                        ])
                        ->where([
                            $conditions
                        ])
                        ->andWhere(['Overtimes.day_of_overtime =' => $day, 'Overtimes.delete_status =' => 0,]);

                array_push($result, [$day, $overtimes->toArray()]);
            }
        }
        $this->set(compact('result'));
        $this->set(compact('overtimes'));
    }

    public function index1() {
        $user = $this->Auth->user();
        $project = $this->getProjectUserLogin($user['user_id']);
    }

    public function getProjectUserLogin($user_id = null) {
        $this->loadModel('Members');

        $query = $this->Members->find()->select(['project_id'])
                ->where([
            'user_id' => $user_id,
            'is_leader' => 1
        ]);

        $arrProject = [];
        foreach ($query->toArray() as $result) {
            array_push($arrProject, $result['project_id']);
        }
        $query = null;
        if ($arrProject) {
            $query = $this->Overtimes->find()->select([
                        'name_project' => 'Projects.name',
                        'Overtimes.manage_overtime_id',
                        'Overtimes.reason',
                        'Overtimes.breaktime',
                        'Overtimes.day_of_overtime',
                        'Overtimes.start_at',
                        'Overtimes.end_at',
                        'name_member' => 'Users.fullname'
                    ])
                    ->order([
                        'Overtimes.day_of_overtime'
                    ])
                    ->join([
                        'table' => 'user_tbl',
                        'alias' => 'Users',
                        'type' => 'LEFT',
                        'conditions' => 'Overtimes.user_id = Users.user_id',
                    ])
                    ->join([
                        'table' => 'project_tbl',
                        'alias' => 'Projects',
                        'type' => 'LEFT',
                        'conditions' => 'Projects.project_id = Overtimes.project_id',
                    ])
                    ->where([
                'Overtimes.project_id IN' => $arrProject,
                'Overtimes.approve_status' => 0,
                'Overtimes.delete_status' => 0,
            ]);
        }
        $this->set('result', $query);
        try {
            $this->paginate($query);
        } catch (NotFoundException $ex) {
            return $this->redirect(['action' => 'index1']);
        }

        // update overtime
        if ($this->request->is('post')) {
            $param = $this->request->getData('manage_overtime');

            if ($param == null) {
                
            } else {
                foreach ($param as $param) {
                    $sql = $this->Overtimes->get($param);
                    $sql = $this->Overtimes->patchEntity($sql, array('approve_status' => '1', 'approver' => $user_id));
                    $this->Overtimes->save($sql);
                }
                return $this->redirect(['controller' => 'Overtimes', 'action' => 'index1']);
            }
        }
    }

    public function index2() {
        $user = $this->Auth->user();
        $project = $this->getProjectUserLogin2($user['user_id']);
    }

    public function getProjectUserLogin2($user_id = null) {

        $query = $this->Overtimes->find()->select([
                    'name_project' => 'Projects.name',
                    'Overtimes.reason',
                    'Overtimes.start_at',
                    'Overtimes.end_at',
                    'Overtimes.breaktime',
                    'Overtimes.day_of_overtime',
                    'name_member' => 'Users.fullname'
                ])
                ->order([
                    'Overtimes.day_of_overtime'
                ])
                ->join([
                    'table' => 'user_tbl',
                    'alias' => 'Users',
                    'type' => 'LEFT',
                    'conditions' => 'Overtimes.user_id = Users.user_id',
                ])
                ->join([
                    'table' => 'project_tbl',
                    'alias' => 'Projects',
                    'type' => 'LEFT',
                    'conditions' => 'Projects.project_id = Overtimes.project_id',
                ])
                ->where([
            'Overtimes.approver' => $user_id,
            'approve_status' => 1,
            'Overtimes.delete_status' => 0,
        ]);
        $this->set('result', $query);
        try {
            $this->paginate($query);
        } catch (NotFoundException $ex) {
            return $this->redirect(['action' => 'index2']);
        }
    }

    public function denyOvertime() {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $param = $this->request->getData();
            $overtime_id = isset($param['overtime_id']) ? $param['overtime_id'] : '';
            $deny_reason = isset($param['deny_reason']) ? $param['deny_reason'] : '';
            $ketqua = [];

            if (!empty($overtime_id)) {

                // Deny Overtime
                $overtime_deny = $this->Overtimes->get($overtime_id);

                $overtime_deny = $this->Overtimes->patchEntity($overtime_deny, [
                    'approve_status' => 2,
                    'deny_reason' => $deny_reason,
                    'approver' => $this->Auth->user()['user_id'],
                        ], ['validate' => 'Deny']);
                $this->Overtimes->save($overtime_deny);
            }
            echo json_encode($overtime_id);
            exit();
        }
    }

    public function listDenyOvertime() {

        $query = $this->Overtimes->find()->select([
                    'name_project' => 'Projects.name',
                    'Overtimes.reason',
                    'Overtimes.start_at',
                    'Overtimes.end_at',
                    'Overtimes.breaktime',
                    'Overtimes.deny_reason',
                    'Overtimes.day_of_overtime',
                    'name_member' => 'Users.fullname'
                ])
                ->order([
                    'Overtimes.day_of_overtime'
                ])
                ->join([
                    'table' => 'user_tbl',
                    'alias' => 'Users',
                    'type' => 'LEFT',
                    'conditions' => 'Overtimes.user_id = Users.user_id',
                ])
                ->join([
                    'table' => 'project_tbl',
                    'alias' => 'Projects',
                    'type' => 'LEFT',
                    'conditions' => 'Projects.project_id = Overtimes.project_id',
                ])
                ->where([
            'Overtimes.approver' => $this->Auth->user()['user_id'],
            'approve_status' => 2,
            'Overtimes.delete_status' => 0,
        ]);
        $this->set('result', $query);


        try {
            $this->paginate($query);
        } catch (NotFoundException $ex) {
            return $this->redirect(['action' => 'listDenyOvertime']);
        }
    }

    // List Total Overtime Each Member

    public function listOvertime() {

        $user_id = $this->Auth->user()['user_id'];

        $query = $this->Overtimes->find();
        $year = $query->func()->year([
            'day_of_overtime' => 'identifier'
        ]);

        $query->select([
            'yearOvertime' => $year,
        ])->distinct('yearOvertime');

        $arrYear = [];
        foreach ($query as $row) {
            $arrYear[-1] = 'Tất cả';
            $arrYear[$row->yearOvertime] = $row->yearOvertime;
        }

        if (empty($arrYear))
            $arrYear[Time::now()->year] = [Time::now()->year];
        $this->set(compact('arrYear'));
        $this->set('currentMonth', -1);
        $this->set('currentYear', -1);


        $conditions = [];
        $data = $this->request->query();

        $day_of_overtime = isset($data['d']) ? $data['d'] : '';
        $name_project = isset($data['np']) ? $data['np'] : '';
        $approve_status = isset($data['s']) ? $data['s'] : '';
        $month = isset($data['m']) ? $data['m'] : null;
        $year = isset($data['y']) ? $data['y'] : null;

        if (isset($name_project) && !empty($name_project)) {
            $conditions['Projects.name LIKE'] = '%' . $name_project . '%';
        }
        if (isset($approve_status) && !empty($approve_status) && $approve_status != -1) {
            $conditions['approve_status'] = $approve_status - 1;
        }
        if (isset($month) && !empty($month) && $month != -1) {
            $conditions['MONTH(Overtimes.day_of_overtime)'] = $month;
        }
        if (isset($year) && !empty($year) && $year != -1) {
            $conditions['YEAR(Overtimes.day_of_overtime)'] = $year;
        }


        $query = $this->Overtimes->find()->select([
                    'Overtimes.day_of_overtime',
                    'name_project' => 'Projects.name',
                    'Overtimes.manage_overtime_id',
                    'Overtimes.start_at',
                    'Overtimes.end_at',
                    'Overtimes.breaktime',
                    'Overtimes.reason',
                    'Overtimes.approve_status',
                    'name_approver' => 'Users.fullname',
                    'Overtimes.deny_reason',
                ])
                ->order([
                    'Overtimes.day_of_overtime' => 'DESC'
                ])
                ->join([
                    'table' => 'user_tbl',
                    'alias' => 'Users',
                    'type' => 'LEFT',
                    'conditions' => 'Overtimes.approver = Users.user_id',
                ])
                ->join([
                    'table' => 'project_tbl',
                    'alias' => 'Projects',
                    'type' => 'LEFT',
                    'conditions' => 'Projects.project_id = Overtimes.project_id',
                ])
                ->where([
                    'Overtimes.delete_status' => 0,
                    'Overtimes.user_id' => $user_id,
                ])
                ->andWhere($conditions);
        try {
            $this->paginate($query);
        } catch (NotFoundException $ex) {
            return $this->redirect(['action' => 'listDenyOvertime']);
        }

        $this->set('result', $query);
        $this->set(compact('data'));
    }

    public function editOvertime($id = null) {
        $overtime = $this->Overtimes->find()->where([
                    'manage_overtime_id' => $id,
                    'user_id' => $this->Auth->user()['user_id'],
                    'approve_status' => 0,
                    'delete_status' => 0,
                ])->first();

        if (empty($overtime)) {
            $this->Flash->myError('Không thể sửa đơn làm thêm');
            return $this->redirect(['controller' => 'Overtimes', 'action' => 'listOvertime']);
        }

        $this->loadModel('ManageOvertimes');

        $project_name = $this->ManageOvertimes->getProjectArr($this->Auth->user()['user_id']);

        $this->set('projects', $project_name);

        $overtime->day_of_overtime = $overtime->day_of_overtime->format('Y-m-d');

        if ($this->request->is('post')) {

            $data = [];

            $data['manage_overtime_id'] = $overtime->manage_overtime_id;
            $data['project_id'] = $this->request->data()['project_id'];
            $data['user_id'] = $this->Auth->user()['user_id'];
            $data['day_of_overtime'] = date("Y-m-d", strtotime($this->request->data['day_of_overtime']));
            $data['start_at'] = $this->request->data['start_at'];
            $data['end_at'] = $this->request->data['end_at'];
            $data['breaktime'] = $this->request->data['breaktime'];
            $data['reason'] = $this->request->data['reason'];

            $overtime = $this->Overtimes->patchEntity($overtime, $data, ['validate' => 'Add']);

            if ($this->ManageOvertimes->save($overtime)) {
                $this->Flash->mySuccess('Sửa yêu cầu làm thêm thành công !');
                return $this->redirect(['action' => 'listOvertime']);
            }
        }
        $this->set(compact('overtime'));
    }

    public function deleteOvertime($id = null) {
        $overtime = $this->Overtimes->find()->where([
                    'manage_overtime_id' => $id,
                    'user_id' => $this->Auth->user()['user_id'],
                    'delete_status' => 0,
                ])->first();

        if (empty($overtime)) {
            $this->Flash->myError('Không thể xóa đơn làm thêm');
            return $this->redirect(['controller' => 'Overtimes', 'action' => 'listOvertime']);
        } else {
            $project = $this->Overtimes->patchEntity($overtime, [
                'delete_status' => '1',
                'deleted_by' => $this->Auth->user()['user_id']]);
            $this->Overtimes->save($project);
            $this->Flash->mySuccess("Đơn làm thêm đã được xóa.");
            return $this->redirect(['action' => 'listOvertime']);
        }
    }

    // Export file CSV Overtime
    public function exportCSV() {
        $this->autoRender = false;
        if ($this->Auth->user()['role'] === 'MEMBERS') {
            return $this->redirect($this->Auth->redirectUrl());
        }
        if ($this->request->is('post')) {
            $data = $this->request->data();
            $month = $data['month'];
            $year = $data['year'];
            $dayOfMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $dayOfWeek = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
            $arrLine1 = ["", ""];
            $arrLine2 = ["No.", "Họ và tên"];
            $arrLineUser = [];
            //Create array have $dayOfMonth + 1 element number zero
            $arrSum = array_fill(1, $dayOfMonth + 1, 0);
            ;
            $arrLineFinal = ["", "Tổng"];

            // Push Line 1 and 2 of file CSV
            for ($i = 1; $i <= $dayOfMonth; $i++) {
                $d = new Time($year . '-' . $month . '-' . $i);
                $day = date('w', strtotime($d->format('Y-m-d')));
                array_push($arrLine1, $dayOfWeek[$day]);
                array_push($arrLine2, date('j', strtotime($d->format('Y-m-d'))));
            }
            array_push($arrLine1, "Tổng số");

            //Push User of file CSV
            foreach ($data['user'] as $key => $user_id) {
                $arrEachUser = [];
                array_push($arrEachUser, $key + 1);
                $this->loadModel('Users');
                $query = $this->Users->find()->select(['fullname'])
                                ->where(['user_id' => $user_id])->first();
                array_push($arrEachUser, $query->fullname);
                $point = $this->Overtimes->getOvertimePoint($user_id, $month, $year);
                foreach ($point as $pos => $row) {
                    $arrSum[$pos] += $row;
                    array_push($arrEachUser, $row);
                }
                array_push($arrLineUser, $arrEachUser);
            }

            //Push line "Tổng"
            foreach ($arrSum as $sum) {
                array_push($arrLineFinal, $sum);
            }

            $filename = "lam-them-gio-" . $month . "-" . $year . ".csv";
            $list = [$arrLine1, $arrLine2];

            foreach ($arrLineUser as $row) {
                array_push($list, $row);
            }

            array_push($list, $arrLineFinal);
            //Download file CSV
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=' . $filename);

            $output = fopen('php://output', 'w');
            //Encode UTF-8
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
            foreach ($list as $fields) {
                fputcsv($output, $fields);
            }

            fclose($output);
        }
    }

}
