<?php

namespace Api\Controller;

use Api\Controller\ApiAppController;
use Cake\ORM\TableRegistry;

class MembersController extends ApiAppController {

    public function index() {
        $this->autoRender = false;
    }

    public function listMember() {

        $this->autoRender = false;
        if ($this->request->is('POST')) {
            $role_id = $this->viewVars['roleID'];
            if ($role_id == 'ADMIN') {
                $users = $this->loadModel('Api.Users');
                $query = $users->find()->select([
                    'user_id',
                    'fullname',
                    'nickname',
                    'create_time',
                    'update_time'
                ]);
                $total = $query->count();

                $arrJson = array(
                    'http' => 200,
                    'message' => 'success',
                    'list-user' => $query,
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

    //Phuong them thanh vien cho du an
    public function add() {
        $this->autoRender = false;

        if ($this->request->is('post')) {
            $param = $this->request->getData();

            $role_id = $this->viewVars['roleID'];
            if ($role_id == 'ADMIN') {
                $addMember = [];
                $addMember['user_id'] = $member_id = isset($param['member_id']) ? $param['member_id'] : '';
                $addMember['project_id'] = $project_id = isset($param['project_id']) ? $param['project_id'] : '';
                $addMember['is_leader'] = $is_leader = isset($param['is_leader']) ? $param['is_leader'] : '';

                $info = $this->Members->find()
                                ->where(['user_id' => $member_id,
                                    'project_id' => $project_id
                                ])->toArray();
                // pr($info);die;

                $member = $this->Members->newEntity($addMember);
                $add = $this->Members->patchEntity($member, $addMember);
//            pr($add);die;
                if (empty($member->errors())) {
                    if (empty($info) || isset($info['user_id'])) {
                        if ($this->Members->save($add)) {
                            $arrJson = array(
                                'http' => 200,
                                'message' => 'success'
                            );
                            die(json_encode($arrJson));
                        }
                    } else {
                        http_response_code('401');
                        $arrJson = array(
                            'http' => 401,
                            'message' => 'error'
                        );
                    }
                    die(json_encode($arrJson));
                } else {
                    http_response_code('400');
                    $arrJson = array(
                        'http' => 400,
                        'message' => 'bad request'
                    );
                    die(json_encode($arrJson));
                }
            } else {
                http_response_code('404');
                $arrJson = array(
                    'http' => 404,
                    'message' => 'Ban khong du tham quyen truy cap trang',
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

    //Phuong xoa thanh vien cua du an
    public function delete() {
        $this->autoRender = false;

        if ($this->request->is("post")) {
            $param = $this->request->getdata();

            $role_id = $this->viewVars['roleID'];
            if ($role_id == 'ADMIN') {
                $member_project_id = isset($param['member_project_id']) ?
                        $param['member_project_id'] : '';


                $checkdata = $this->Members->find('all')
                        ->where(['member_project_id' => $member_project_id]);

                $info = $checkdata->toArray();

                if (empty($info)) {
                    http_response_code('401');
                    $arrJson = array(
                        'http' => 401,
                        'message' => 'error'
                    );

                    die(json_encode($arrJson));
                }

                $member = $this->Members->get($member_project_id);
                if ($this->Members->delete($member)) {
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
                    'message' => 'Ban khong du tham quyen truy cap trang',
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

    //Phuong thiet lap leader cho du an
    public function leader() {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $param = $this->request->getdata();

            $role_id = $this->viewVars['roleID'];
            if ($role_id == 'ADMIN') {
                $addLeader['member_project_id'] = $member_project_id = isset($param['member_project_id']) ? $param['member_project_id'] : '';
                $addLeader['is_leader'] = MEMBER_LEADER;

                $newRow = $this->Members->newEntity($addLeader);

                if (empty($member_project_id)) {
                    http_response_code('400');
                    $arrJson = array(
                        'http' => 400,
                        'message' => 'validate fail'
                    );
                    die(json_encode($arrJson));
                }

                $info = $this->Members->find()
                                ->join([
                                    'table' => 'project_tbl',
                                    'type' => 'LEFT',
                                    'conditions' => 'Members.project_id = project_tbl.project_id'
                                ])
                                ->where(['project_tbl.status' => PROJECT_STATUS_PENDDING])
                                ->orwhere(['project_tbl.status' => PROJECT_STATUS_NOPENDDING])
                                ->where(['Members.is_leader' => MEMBER_NOLEADER,
                                    'Members.member_project_id' => $member_project_id
                                ])->toArray();

                if (empty($info)) {
                    http_response_code('404');
                    $arrJson = array(
                        'http' => 404,
                        'message' => 'not found'
                    );
                    die(json_encode($arrJson));
                } else {

                    $member = $this->Members->get($member_project_id);
                    $this->Members->patchEntity($member, $addLeader);
                    if ($this->Members->save($member)) {
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
                http_response_code('404');
                $arrJson = array(
                    'http' => 404,
                    'message' => 'Ban khong du tham quyen truy cap trang',
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

}
