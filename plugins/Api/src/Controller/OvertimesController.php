<?php

namespace Api\Controller;

use Cake\Event\Event;
use Api\Controller\ApiAppController;

class OvertimesController extends ApiAppController {

    /**
     * xem danh sách các ngày trong tháng: hiển thị ngày làm thêm, 
     * dự án nào, lí do, có được duyệt k, ai duyệt
     *
     * @param :user_id : id cua user login
     * @return: array json list_dayovertime
     */
    public function listDay() {

        $this->autoRender = false;

        if ($this->request->is('POST')) {
            $data = $this->request->getData();
            if ($data) {
                $arrJson = $this->Overtimes->listAllDay($data);
            } else {
                http_response_code('404');
                $arrJson = array(
                    'http' => 404,
                    'message' => 'not found',
                );
            }
            die(json_encode($arrJson));
        } else {
            http_response_code('404');
            $arrJson = array(
                'http' => 404,
                'message' => 'not found',
            );
            die(json_encode($arrJson));
        }
    }

    /**
     * danh sách các yêu cầu làm thêm giờ chưa được phê duyệt
     * của các dự án user tham gia với vai trò là leader
     *
     * @param :user_id : id cua user login
     * @return: array json list_dayovertime
     */
    public function notApproved() {
        $this->autoRender = false;
        if ($this->request->is('POST')) {
            $data = $this->request->getData();
            if ($data) {
                $arrJson = $this->Overtimes->getListOvertime($data);
            } else {
                http_response_code('404');
                $arrJson = array(
                    'http' => 404,
                    'message' => 'not found',
                );
            }
            die(json_encode($arrJson));
        } else {
            http_response_code('404');
            $arrJson = array(
                'http' => 404,
                'message' => 'not found',
            );
            die(json_encode($arrJson));
        }
    }

    /**
     * danh sách các yêu cầu làm thêm giờ đã được phê duyệt
     * của các dự án user tham gia với vai trò là leader
     * 
     * @param :user_id : id cua userlogin
     * @return: array json list_dayovertime
     */
    public function approved() {
        $this->autoRender = false;
        if ($this->request->is('POST')) {
            $data = $this->request->getData();
            if ($data) {
                $arrJson = $this->Overtimes->getListOvertimeApproved($data);
            } else {
                http_response_code('404');
                $arrJson = array(
                    'http' => 404,
                    'message' => 'not found',
                );
            }
            die(json_encode($arrJson));
        } else {
            http_response_code('404');
            $arrJson = array(
                'http' => 404,
                'message' => 'not found',
            );
            die(json_encode($arrJson));
        }
    }

    /**
     * tạo yêu cầu làm thêm giờ cho dự án mà bản thân user tham gia
     * 
     * @param: user_id: user by user login,project_id: du an truyen vao
     * 		 day_of_overtime: ngay lam them, reason: ly do 
     * 
     *
     */
    public function create() {

        $this->autoRender = false;

        if ($this->request->is('post')) {
            // get param
            $param = $this->request->getData();

            $addEvent['user_id'] = $member_id = isset($param['user_id']) ? $param['user_id'] : '';
            $addEvent['project_id'] = $project_id = isset($param['project_id']) ? $param['project_id'] : '';
            $addEvent['day_of_overtime'] = $day_of_overtime = isset($param['day_of_overtime']) ? $param['day_of_overtime'] : '';
            $addEvent['reason'] = $reason = isset($param['reason']) ? $param['reason'] : '';
            $addEvent['start_at'] = $start_at = isset($param['start_at']) ? $param['start_at'] : '';
            $addEvent['end_at'] = $end_at = isset($param['end_at']) ? $param['end_at'] : '';
            $addEvent['breaktime'] = $breaktime = isset($param['breaktime']) ? $param['breaktime'] : '';
            $addEvent['created_by'] = $member_id;

            $newRow = $this->Overtimes->newEntity($addEvent);

            //check validate
            if (!empty($newRow->errors()) || empty($reason)) {
                http_response_code('401');
                $arrJson = array(
                    'http' => 401,
                    'message' => 'validate fail'
                );
                die(json_encode($arrJson));
            }


            //check user_id là thanh vien cua du an va du an co dang tham gia?
            $info_member = $this->checkIsMember($project_id, $member_id);
            if (empty($info_member)) {
                http_response_code('404');
                $arrJson = array(
                    'http' => 404,
                    'message' => 'not found'
                );
                die(json_encode($arrJson));
            }

            foreach ($info_member as $v) {
                $start_date = $v->start_date;
                $end_date = $v->end_date;
            }

            //gọi hàm check đã tạo yêu cầu làm thêm
            $checkCreated = $this->checkCreated($member_id, $project_id, $day_of_overtime, $start_at, $end_at);
            if (!empty($checkCreated)) {
                http_response_code('401');
                $arrJson = array(
                    'http' => 401,
                    'message' => 'error'
                );
                die(json_encode($arrJson));
            } else {
                if (strtotime($end_date) == NULL) {
                    if (strtotime($day_of_overtime) >= strtotime($start_date) && strtotime($start_at) < strtotime($end_at)) {
                        if ($this->Overtimes->save($this->Overtimes->patchEntity($newRow, $addEvent))) {
                            $arrJson = array(
                                'http' => 200,
                                'message' => 'success'
                            );
                        } else {
                            http_response_code('401');
                            $arrJson = array(
                                'http' => 401,
                                'message' => 'error'
                            );
                        }
                        die(json_encode($arrJson));
                    } else {
                        http_response_code('401');
                        $arrJson = array(
                            'http' => 401,
                            'message' => 'error'
                        );
                        die(json_encode($arrJson));
                    }
                } else {
                    if (strtotime($day_of_overtime) >= strtotime($start_date) && strtotime($day_of_overtime) <= strtotime($end_date) && strtotime($start_at) < strtotime($end_at)) {
                        if ($this->Overtimes->save($this->Overtimes->patchEntity($newRow, $addEvent))) {
                            $arrJson = array(
                                'http' => 200,
                                'message' => 'success'
                            );
                        } else {
                            http_response_code('401');
                            $arrJson = array(
                                'http' => 401,
                                'message' => 'error'
                            );
                        }
                        die(json_encode($arrJson));
                    } else {
                        http_response_code('401');
                        $arrJson = array(
                            'http' => 401,
                            'message' => 'error'
                        );
                        die(json_encode($arrJson));
                    }
                }
            }
        } else {
            http_response_code('404');
            $arrJson = array(
                'http' => 404,
                'message' => 'not found',
            );
            die(json_encode($arrJson));
        }
    }

    /**
     * tạo yêu cầu làm thêm giờ cho member của dự án mà
     * user tham gia với vai trò là leader
     * 
     * @param: user_login: id cua user login,
     * 		 user_id: id cua 1 thanh vien trong du an
     * 		 project_id: id cua du an
     * 		 day_of_overtime: ngay lam them, 
     * 		 reason: li do lam them,
     * 		 
     *
     */
    public function createbyLeader() {


        $this->autoRender = false;

        if ($this->request->is('post')) {
            // get param
            $param = $this->request->getData();
            $newRow = $this->Overtimes->newEntity();

            $addEvent = [];

            $user_login = isset($param['user_id']) ? $param['user_id'] : '';
            $addEvent['user_id'] = $member_id = isset($param['member_id']) ? $param['member_id'] : '';
            $addEvent['project_id'] = $project_id = isset($param['project_id']) ? $param['project_id'] : '';
            $addEvent['day_of_overtime'] = $day_of_overtime = isset($param['day_of_overtime']) ? $param['day_of_overtime'] : '';
            $addEvent['reason'] = $reason = isset($param['reason']) ? $param['reason'] : '';
            $addEvent['start_at'] = $start_at = isset($param['start_at']) ? $param['start_at'] : '';
            $addEvent['end_at'] = $end_at = isset($param['end_at']) ? $param['end_at'] : '';
            $addEvent['breaktime'] = $breaktime = isset($param['breaktime']) ? $param['breaktime'] : '';
            $addEvent['approve_status'] = MANAGE_STATUS_APPROVED;
            $addEvent['approver'] = $user_login;
            $addEvent['created_by'] = $user_login;

            $newRow = $this->Overtimes->newEntity($addEvent);

            if (!empty($newRow->errors()) || empty($reason)) {

                http_response_code('401');
                $arrJson = array(
                    'http' => 401,
                    'message' => 'validate fail'
                );
                die(json_encode($arrJson));
            }

            //gọi hàm check leader lấy ngày bắt đầu dự án và kết thúc dự án
            $checkLeader = $this->checkLeader($user_login, $project_id);

            //gọi hàm check user_id là thành viên dự án
            $checkIsMember = $this->checkIsMember($project_id, $member_id);

            if (empty($checkLeader) || empty($checkIsMember)) {
                http_response_code('400');
                $arrJson = array(
                    'http' => 400,
                    'message' => 'bad request'
                );
                die(json_encode($arrJson));
            }

            foreach ($checkIsMember as $v) {
                $start_date = $v->start_date;
                $end_date = $v->end_date;

                //gọi hàm check đã tạo yêu cầu làm thêm
                $checkCreated = $this->checkCreated($member_id, $project_id, $day_of_overtime, $start_at, $end_at);

                if (!empty($checkCreated)) {
                    http_response_code('401');
                    $arrJson = array(
                        'http' => 401,
                        'message' => 'error'
                    );
                    die(json_encode($arrJson));
                } else {
                    if (strtotime($end_date) == NULL) {
                        if (strtotime($day_of_overtime) >= strtotime($start_date) && strtotime($start_at) < strtotime($end_at)) {
                            if ($this->Overtimes->save($this->Overtimes->patchEntity($newRow, $addEvent))) {
                                $arrJson = array(
                                    'http' => 200,
                                    'message' => 'success'
                                );
                            } else {
                                http_response_code('401');
                                $arrJson = array(
                                    'http' => 401,
                                    'message' => 'error'
                                );
                            }
                            die(json_encode($arrJson));
                        } else {
                            http_response_code('401');
                            $arrJson = array(
                                'http' => 401,
                                'message' => 'error'
                            );
                            die(json_encode($arrJson));
                        }
                    } else {
                        if (strtotime($day_of_overtime) >= strtotime($start_date) && strtotime($day_of_overtime) <= strtotime($end_date) && strtotime($start_at) < strtotime($end_at)) {
                            if ($this->Overtimes->save($this->Overtimes->patchEntity($newRow, $addEvent))) {
                                $arrJson = array(
                                    'http' => 200,
                                    'message' => 'success'
                                );
                            } else {
                                http_response_code('401');
                                $arrJson = array(
                                    'http' => 401,
                                    'message' => 'error'
                                );
                            }
                            die(json_encode($arrJson));
                        } else {
                            http_response_code('401');
                            $arrJson = array(
                                'http' => 401,
                                'message' => 'error'
                            );
                            die(json_encode($arrJson));
                        }
                    }
                }
            }
        } else {
            http_response_code('404');
            $arrJson = array(
                'http' => 404,
                'message' => 'not found',
            );
            die(json_encode($arrJson));
        }
    }

    /**
     * phe duyet yeu cau lam them gio cho thanh vien 
     * cua du an ma user tham gia voi vai tro leader
     *
     * @param: user_login: id cua user login,
     * 		 user_id: id cua 1 thanh vien trong du an
     * 		 project_id: id cua du an
     * 		 day_of_overtime: ngay lam them, 
     * 		 approve_status: duyet yeu cau
     */
    public function approve() {
        $this->autoRender = false;

        if ($this->request->is('post')) {
            // get param
            $param = $this->request->getData();

            $addEvent = [];

            $user_login = isset($param['user_id']) ? $param['user_id'] : '';
            $addEvent['manage_overtime_id'] = $manage_overtime_id = isset($param['manage_overtime_id']) ? $param['manage_overtime_id'] : '';
            $addEvent['deny_reason'] = $deny_reason = isset($param['deny_reason']) ? $param['deny_reason'] : NULL;
            if (empty($deny_reason)) {
                $addEvent['approve_status'] = $approve_status = MANAGE_STATUS_APPROVED;
            } else {
                $addEvent['approve_status'] = $approve_status = MANAGE_STATUS_DENY_APPROVED;
            }
            $addEvent['approver'] = $user_login;

            $newRow = $this->Overtimes->newEntity($addEvent);

            if (!empty($newRow->errors())) {
                http_response_code('400');
                $arrJson = array(
                    'http' => 400,
                    'message' => 'validate fail'
                );
                die(json_encode($arrJson));
            }

            //lấy yêu cầu làm thêm giờ của user_id chưa được phê duyệt
            $approved = $this->Overtimes->find()
                            ->where([
                                'Overtimes.manage_overtime_id' => $manage_overtime_id,
                                'Overtimes.approve_status' => MANAGE_STATUS_NOAPPROVED,
                                'Overtimes.delete_status' => NODELETE_STATUS
                            ])->toArray();

            if (empty($approved)) {
                http_response_code('404');
                $arrJson = array(
                    'http' => 404,
                    'message' => 'not found'
                );
                die(json_encode($arrJson));
            }

            foreach ($approved as $v) {
                $project_id = $v->project_id;
            }

            $checkLeader = $this->checkLeader($user_login, $project_id);

            if (empty($checkLeader)) {
                http_response_code('400');
                $arrJson = array(
                    'http' => 400,
                    'message' => 'bad request'
                );
                die(json_encode($arrJson));
            }

            $edit = $this->Overtimes->get($manage_overtime_id);
            $this->Overtimes->patchEntity($edit, $addEvent);

            if ($this->Overtimes->save($edit)) {
                $arrJson = array(
                    'http' => 200,
                    'message' => 'success'
                );
            } else {
                http_response_code('401');
                $arrJson = array(
                    'http' => 401,
                    'message' => 'error'
                );
            }
            die(json_encode($arrJson));
        } else {
            http_response_code('404');
            $arrJson = array(
                'http' => 404,
                'message' => 'not found',
            );
            die(json_encode($arrJson));
        }
    }

    //check user_login co phai la leader? + du an co dang tham gia k?
    public function checkLeader($user_login, $project_id) {
        $members = $this->loadModel('Api.Members');
        $info = $members->find()
                        ->select([
                            'Members.project_id'
                        ])->join([
                    'table' => 'project_tbl',
                    'type' => 'LEFT',
                    'conditions' => 'Members.project_id = project_tbl.project_id '
                ])->where([
                    'Members.user_id' => $user_login,
                    'Members.project_id' => $project_id,
                    'Members.is_leader' => MEMBER_LEADER,
                    'project_tbl.status' => PROJECT_STATUS_PENDDING
                ])->toArray();
        return $info;
    }

    //check user_id co la thanh vien cua du an k?
    public function checkIsMember($project_id, $member_id) {
        $members = $this->loadModel('Api.Members');
        $info_member = $members->find()
                        ->select([
                            'Members.project_id',
                            'start_date' => 'project_tbl.start_date',
                            'end_date' => 'project_tbl.end_date'
                        ])->join([
                    'table' => 'project_tbl',
                    'type' => 'LEFT',
                    'conditions' => 'Members.project_id = project_tbl.project_id'
                ])->where([
                    'Members.user_id' => $member_id,
                    'Members.project_id' => $project_id,
                    'project_tbl.status' => PROJECT_STATUS_PENDDING
                ])->toArray();
        return $info_member;
    }

    //check user đã tạo yêu cầu làm thêm chưa?
    public function checkCreated($member_id, $project_id, $day_of_overtime, $start_at, $end_at) {
        $manages = $this->loadModel('Api.Overtimes');
        $info_created = $manages->find()
                        ->select([
                            'Overtimes.manage_overtime_id',
                        ])->where([
                    'Overtimes.user_id' => $member_id,
                    'Overtimes.project_id' => $project_id,
                    'Overtimes.day_of_overtime' => $day_of_overtime,
                    'Overtimes.start_at <=' => $end_at,
                    'Overtimes.end_at >=' => $start_at
                ])->toArray();
        return $info_created;
    }

    // Sua yeu cau lam them neu chua duoc phe duyet
    public function edit() {
        $this->autoRender = false;

        if ($this->request->is('post')) {
            // get param
            $param = $this->request->getData();

            $user_login = isset($param['user_id']) ? $param['user_id'] : '';
            $addEvent['updated_by'] = $user_login;
            $addEvent['manage_overtime_id'] = $manage_overtime_id = isset($param['manage_overtime_id']) ? $param['manage_overtime_id'] : '';
            $addEvent['day_of_overtime'] = $day_of_overtime = isset($param['day_of_overtime']) ? $param['day_of_overtime'] : '';
            $addEvent['reason'] = $reason = isset($param['reason']) ? $param['reason'] : '';
            $addEvent['breaktime'] = $breaktime = isset($param['breaktime']) ? $param['breaktime'] : '';
            $addEvent['start_at'] = $start_at = isset($param['start_at']) ? $param['start_at'] : '';
            $addEvent['end_at'] = $end_at = isset($param['end_at']) ? $param['end_at'] : '';


            $newRow = $this->Overtimes->newEntity($addEvent);

            if (!empty($newRow->errors())) {
                http_response_code('400');
                $arrJson = array(
                    'http' => 400,
                    'message' => 'validate fail'
                );
                die(json_encode($arrJson));
            }
            //check yêu cầu đã được phê duyệt by leader?
            $check = $this->checkAppoved($manage_overtime_id, $user_login);

            if (!empty($check)) {

                $edit = $this->Overtimes->get($manage_overtime_id);
                $this->Overtimes->patchEntity($edit, $addEvent);
                if ($this->Overtimes->save($edit)) {
                    $arrJson = array(
                        'http' => 200,
                        'message' => 'success'
                    );
                } else {
                    http_response_code('401');
                    $arrJson = array(
                        'http' => 401,
                        'message' => 'error'
                    );
                }
                die(json_encode($arrJson));
            } else {
                http_response_code('401');
                $arrJson = array(
                    'http' => 401,
                    'message' => 'error'
                );
                die(json_encode($arrJson));
            }
        } else {
            http_response_code('404');
            $arrJson = array(
                'http' => 404,
                'message' => 'not found'
            );
            die(json_encode($arrJson));
        }
    }

    public function delete() {
        $this->autoRender = false;

        if ($this->request->is('post')) {
            // get param
            $param = $this->request->getData();

            $user_login = isset($param['user_id']) ? $param['user_id'] : '';
            $addEvent['deleted_by'] = $user_login;
            $addEvent['manage_overtime_id'] = $manage_overtime_id = isset($param['manage_overtime_id']) ? $param['manage_overtime_id'] : '';
            $addEvent['delete_status'] = DELETE_STATUS;

            $newRow = $this->Overtimes->newEntity($addEvent);

            if (!empty($newRow->errors())) {
                http_response_code('400');
                $arrJson = array(
                    'http' => 400,
                    'message' => 'validate fail'
                );
                die(json_encode($arrJson));
            }
            //check yêu cầu đã được phê duyệt by leader?
            $check = $this->checkAppoved($manage_overtime_id, $user_login);

            if (!empty($check)) {
                foreach ($check as $manages) {
                    $delete = $this->Overtimes->get($manage_overtime_id);
                    $this->Overtimes->patchEntity($delete, $addEvent);

                    if ($this->Overtimes->save($delete)) {
                        $arrJson = array(
                            'http' => 200,
                            'message' => 'success'
                        );
                    } else {
                        http_response_code('401');
                        $arrJson = array(
                            'http' => 401,
                            'message' => 'error'
                        );
                    }
                    die(json_encode($arrJson));
                }
            } else {
                http_response_code('401');
                $arrJson = array(
                    'http' => 401,
                    'message' => 'error'
                );
                die(json_encode($arrJson));
            }
        } else {
            http_response_code('404');
            $arrJson = array(
                'http' => 404,
                'message' => 'not found'
            );
            die(json_encode($arrJson));
        }
    }

    //lay ra yeu cau lam them gio bi tu choi
    public function deny() {
        $this->autoRender = false;

        if ($this->request->is('POST')) {
            $data = $this->request->getData();
            if ($data) {
                $arrJson = $this->Overtimes->getListDeny($data);
                die(json_encode($arrJson));
            } else {
                http_response_code('404');
                $arrJson = array(
                    'http' => 404,
                    'message' => 'not found',
                );
                die(json_encode($arrJson));
            }
        } else {
            http_response_code('404');
            $arrJson = array(
                'http' => 404,
                'message' => 'not found',
            );
            die(json_encode($arrJson));
        }
    }

    // check yêu cầu đã phê duyệt chưa? 
    public function checkAppoved($manage_overtime_id, $user_login) {
        $overtimes = $this->loadModel('Api.Overtimes');
        $checkAppoved = $overtimes->find()
                        ->where([
                            'Overtimes.manage_overtime_id' => $manage_overtime_id,
                            'user_id' => $user_login,
                            'Overtimes.approve_status' => MANAGE_STATUS_NOAPPROVED,
                            'Overtimes.delete_status' => NODELETE_STATUS
                        ])->toArray();
        return $checkAppoved;
    }

    //Phuong xem thong tin lam them gio cua user(test tren postman)
    public function Info() {
        $this->autoRender = false;

        if ($this->request->is("POST")) {
            $role_id = $this->viewVars['roleID'];
            if ($role_id == 'ADMIN') {
                $day_of_overtime = $this->Overtimes->getformatDate();
                $query = $this->Overtimes->find()->select([
                                    'user.user_id',
                                    'user.fullname',
                                    'project_id',
                                    'day_overtime' => $day_of_overtime,
                                    'reason',
                                    'approve_status',
                                    'approver',
                                    'deny_reason',
                                    'start_at',
                                    'end_at',
                                    'breaktime'
                                ])
                                ->join([
                                    'table' => 'user_tbl',
                                    'alias' => 'user',
                                    'type' => 'RIGHT',
                                    'conditions' => 'Overtimes.user_id = user.user_id',
                                ])->where(['Overtimes.delete_status' => NODELETE_STATUS]);

                $param = $this->request->getData();
                $info = isset($param['member_id']) ? $param['member_id'] : '';
                if (!empty($info)) {
                    $query->where(['user.user_id' => $info]);
                }

                $total = $query->count();
                $arrJson = array(
                    'http' => 200,
                    'message' => 'success',
                    'overtimes' => $query,
                    'total' => $total
                );
            } else {
                http_response_code('404');
                $arrJson = array(
                    'http' => 404,
                    'message' => 'Ban khong du tham quyen truy cap trang',
                );
            }
            die(json_encode($arrJson));
        } else {
            http_response_code('404');
            $arrJson = array(
                'http' => 404,
                'message' => 'not found',
            );
            die(json_encode($arrJson));
        }
    }

}
