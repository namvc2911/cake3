<?php

namespace App\Controller;

use Cake\Event\Event;
use App\Controller\AppController;

class ManageOvertimesController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->viewBuilder()->setLayout('my_layout');
    }

    public function calendar() {
        $user = $this->Auth->user();

        $user_id = $user['user_id'];

        $manage = $this->loadModel('ManageOvertimes');

        $project_name = $manage->getProject($user_id);


        $date_of_overtime = $manage->getDayOfOvertime($user_id);

        $date_Obj = (object) $date_of_overtime;

        $this->set('date', json_encode($date_Obj));

        $reason = $manage->getReason($user_id);
        
        $deny_reason = $manage->getDenyReason($user_id);

        $approve_stt = $this->ManageOvertimes->testApproveStatus($user_id);

        // lay ten nguoi phe duyet
        $approver = $this->ManageOvertimes->getApprover($user_id);

        $this->set('approver', json_encode($approver));

        $name_user = $this->ManageOvertimes->getNameUser($user_id);
        
        $start_at = $this->ManageOvertimes->getStartAt($user_id);
        
        $end_at = $this->ManageOvertimes->getEndAt($user_id);

        $all = [];
        $count = 0;
        $count_deny = 0;
        for ($i = 0; $i < count($date_of_overtime); $i++) {

            $all[$i]['date'] = $date_of_overtime[$i];

            $all[$i]['project'] = $project_name[$i];

            $all[$i]['name'] = $name_user[0];
            if ($approve_stt[$i] == 1) {
                $all[$i]['approver'] = $approver[$count];
                $count++;
            }
            if ($approve_stt[$i] == 2) {
                $all[$i]['approver'] = $approver[$count];
                $count++;
                $all[$i]['deny_reason'] = $deny_reason[$count_deny];
                $count_deny++;
            }
            $all[$i]['reason'] = $reason[$i];
            
            $all[$i]['start_at'] = $start_at[$i];
            
            $all[$i]['end_at'] = $end_at[$i];

            $all[$i]['sttapprover'] = $approve_stt[$i];
        }
        // Convert Special Character
        $arr_start = ["'",'"'];
        $arr_replace = ["\'",'\"'];
        $result = str_replace($arr_start,$arr_replace,json_encode($all,JSON_UNESCAPED_UNICODE));
        $this->set('allObj', $result);
    }

    public function addrequest() {

        $user = $this->Auth->user();

        $user_id = $user['user_id'];

        $manage = $this->loadModel('ManageOvertimes');

        $project_name = $manage->getProjectArr($user_id);
        
        $this->set('projects', $project_name);

        if (empty($project_name)) {
            return $this->redirect(['controller' => 'Projects', 'action' => 'index']);
        }

        $add = $this->ManageOvertimes->newEntity();
        if ($this->request->is('post')) {
            $addEvent = [];
            $addEvent['user_id'] = $user_id;
            $addEvent['project_id'] = $this->request->data['project_id'];
            $addEvent['day_of_overtime'] = date("Y-m-d", strtotime($this->request->data['day_overtime']));
            $addEvent['start_at'] = $this->request->data['start_at'];
            $addEvent['end_at'] = $this->request->data['end_at'];
            $addEvent['breaktime'] = $this->request->data['breaktime'];
            $addEvent['day_overtime'] = $this->request->data['day_overtime'];
            $addEvent['reason'] = $this->request->data['reason'];
            $addEvent['approve_stt'] = 0;
            $addEvent['delete_status'] = 0;
            $addEvent['created_by'] = $user_id;

            $add = $this->ManageOvertimes->patchEntity($add, $addEvent, ['validate' => 'Add']);

            if ($this->ManageOvertimes->save($add)) {
                $this->Flash->mySuccess('Bạn đã tạo yêu cầu thành công!');
                return $this->redirect(['action' => 'calendar']);
            }
        }
        $this->set('add', $add);
    }

    //task 5
    public function addrequestbyleader() {
        $user_id;
        $user = $this->Auth->user();
        
        if ($user['user_id']) {
            $user_id = $user['user_id'];

            $manage = $this->loadModel('ManageOvertimes');

            $project_name = $manage->getProjectIsLeader($user_id);
            $this->set('projects', $project_name);


            if (empty($project_name)) {
                return $this->redirect(['controller' => 'Projects', 'action' => 'index']);
            }

            $project_id = $manage->getIdProjectLeader($user_id);
            $this->set('project_id', $project_id);

            $member = $manage->getMemberProjectLeader($project_id[0]);
            $this->set('users', $member);

            $addByLeader = $this->ManageOvertimes->newEntity();
            if ($this->request->is('post')) {
                    
                    $addEventByLeader = [];

                    $addEventByLeader['user_id'] = $this->request->data['user_id'];
                    $addEventByLeader['project_id'] = $this->request->data['project_id'];


                    $addEventByLeader['day_of_overtime'] = date("Y-m-d", strtotime($this->request->data['day_overtime']));
                    $addEventByLeader['day_overtime'] = $this->request->data['day_overtime'];
                    $addEventByLeader['start_at'] = $this->request->data['start_at'];
                    $addEventByLeader['end_at'] = $this->request->data['end_at'];
                    $addEventByLeader['breaktime'] = $this->request->data['breaktime'];
                    $addEventByLeader['reason'] = $this->request->data['reason'];
                    $addEventByLeader['approve_status'] = 1;
                    $addEventByLeader['approver'] = $user_id;
                    $addEventByLeader['created_by'] = $user_id;
                    $addEventByLeader['delete_status'] = 0;
                    $addByLeader = $this->ManageOvertimes->patchEntity($addByLeader, $addEventByLeader, ['validate' => 'Add']);
//                    pr($addEventByLeader);die;
                    if ($this->ManageOvertimes->save($addByLeader)) {
                        $this->Flash->mySuccess('Bạn đã tạo yêu cầu thành công!');
                        echo 1;
                        if ($this->request->data['user_id'] != $user_id) {
                            return $this->redirect(['action' => 'calendar']);
                        } else {
                            return $this->redirect(['controller' => 'home', 'action' => 'index']);
                        }
                    }
            }
            $this->set('addByLeader', $addByLeader);
        }
    }

    public function getMemberProject() {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $param = $this->request->getData();
            $project_id = isset($param['project_id']) ? $param['project_id'] : '';

            $result = [];
            if (!empty($project_id)) {
                $this->loadModel("MemberProject");

                $query = $this->MemberProject->find()->select([
                            'user_id' => 'MemberProject.user_id',
                            'name_user' => 'Users.fullname'
                        ])
                        ->join([
                            'table' => 'user_tbl',
                            'alias' => 'Users',
                            'type' => 'LEFT',
                            'conditions' => 'MemberProject.user_id = Users.user_id',
                        ])
                        ->where(['MemberProject.project_id' => $project_id])
                        ->order(['name_user' => 'ASC']);
                foreach($query->toArray() as $row){
                    $result[] = array(
                        'user_id' => $row['user_id'],
                        'name_user' => $row['name_user']
                    );
                }
            }
            die(json_encode($result));
        }
    }

}
