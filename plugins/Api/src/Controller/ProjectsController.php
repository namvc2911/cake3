<?php

namespace Api\Controller;

use Api\Controller\ApiAppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;

class ProjectsController extends ApiAppController {

    public function index() {

        $this->autoRender = false;
    }

    /** Thu
     * Hiển thị danh sách dự án đã và đang thamg gia của user login
     *
     * @param user_id : id of userlogin
     * @return: array json projects
     */
    public function listProject() {
        $this->autoRender = false;

        if ($this->request->is('POST')) {
            $data = $this->request->getData();
            if ($data) {
                $arrJson = $this->Projects->getProjects($data);
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

    /** Thu
     * Xem thông tin dự án(danh sách thành viên, leader) của user login
     * @param user_id : id of userlogin
     * @return: array json memberProject 
     */
    public function listMember() {
        $this->autoRender = false;
        if ($this->request->is('POST')) {
            $data = $this->request->getdata();
            if ($data) {
                $projects = $this->Projects->getProjects($data);

                $member = $this->loadModel('Api.Members');
                $arrJson = $member->getInfoMember($projects);
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

    //Phuong danh sach du an
    public function listProjects() {
        $this->autoRender = false;

        if ($this->request->is('POST')) {
            $role_id = $this->viewVars['roleID'];
            if ($role_id == 'ADMIN') {
                $arrJson = $this->Projects->getProject();
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

    //Phuong xem thong tin du an (members, leader)
    public function Info() {
        $this->autoRender = false;
        if ($this->request->is('POST')) {
            $role_id = $this->viewVars['roleID'];
            if ($role_id == 'ADMIN') {
                $projects = $this->Projects->getAllProject();
                $member = $this->loadModel('Api.Members');
                $arrJson = $member->getAllMember($projects);
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
                'message' => 'not found'
            );
            die(json_encode($arrJson));
        }
    }

    //Phuong tao moi du an
    public function add() {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $param = $this->request->getData();

            $role_id = $this->viewVars['roleID'];
            if ($role_id == 'ADMIN') {
                $name = isset($param['name']) ? $param['name'] : '';
                $description = isset($param['description']) ? $param['description'] : '';
                $start_date = isset($param['start_date']) ? $param['start_date'] : '';
                $end_date = isset($param['end_date']) ? $param['end_date'] : '';

                if (isset($param['start_date'])) {
                    $param['start_date'] = date('Y-m-d', strtotime($param['start_date']));
                }

                if (isset($param['end_date'])) {
                    $param['end_date'] = date('Y-m-d', strtotime($param['end_date']));
                }
                $status = isset($param['status']) ? $param['status'] : '';

                $project = $this->loadModel('Api.Projects');
                $query = $project->find()->where([
                            'name' => $name,
                            'start_date' => $start_date,
                            'end_date' => $end_date
                        ])->toArray();

                if (empty($query)) {
                    $project = $this->Projects->newEntity();
                    $add = $this->Projects->patchEntity(
                            $project, $param, ['validate' => 'Add']);

                    if (!empty($project->errors())) {
                        http_response_code('401');
                        $arrJson = array(
                            'http' => 401,
                            'message' => 'validate fail'
                        );
                        die(json_encode($arrJson));
                    }
                    if ($end_date == NULL) {
                        if ($this->Projects->save($add)) {
                            $arrJson = array(
                                'http' => 200,
                                'message' => 'success'
                            );
                            die(json_encode($arrJson));
                        }
                        } else {
                            if ($project->start_date < $project->end_date) {
                                if ($this->Projects->save($add)) {
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

    //Phuong sua du an
    public function edit() {
        $this->autoRender = false;

        if ($this->request->is("post")) {
            $param = $this->request->getdata();

            $role_id = $this->viewVars['roleID'];
            if ($role_id == 'ADMIN') {
                $name = isset($param['name']) ? $param['name'] : '';
                $project_id = isset($param['project_id']) ? $param['project_id'] : '';
                $description = isset($param['description']) ? $param['description'] : '';
                $start_date = isset($param['start_date']) ? $param['start_date'] : '';
                $end_date = isset($param['end_date']) ? $param['end_date'] : '';

                if (isset($param['start_date'])) {
                    $param['start_date'] = date('Y-m-d', strtotime($param['start_date']));
                }

                if (isset($param['end_date'])) {
                    $param['end_date'] = date('Y-m-d', strtotime($param['end_date']));
                }
                $status = isset($param['status']) ? $param['status'] : '';

                if ($project_id == "") {
                    http_response_code('400');
                    $arrJson = array(
                        'http' => 400,
                        'message' => 'Validate fail'
                    );
                    die(json_encode($arrJson));
                }

                $project = $this->Projects->find()
                        ->where(['status' => PROJECT_STATUS_PENDDING])
                        ->orwhere(['status' => PROJECT_STATUS_NOPENDDING])
                        ->where(['project_id' => $project_id])
                        ->toArray();
                if (!empty($project)) {
                    foreach ($project as $projects) {
                        $projects = $this->Projects->get($project_id);
                        $edit = $this->Projects->patchEntity($projects, $param, ['validate' => 'Edit']);

                        if ($projects['status'] == PROJECT_STATUS_NOPENDDING) { // status = 1
                            if (!empty($projects->errors())) {
                                http_response_code('401');
                                $arrJson = array(
                                    'http' => 401,
                                    'message' => 'error'
                                );
                                die(json_encode($arrJson));
                            }
                            if ($end_date == NULL) {
                                if ($this->Projects->save($edit)) {
                                    $arrJson = array(
                                        'http' => 200,
                                        'message' => 'success'
                                    );
                                }
                            } else {
                                if ($projects->start_date > $projects->end_date) {
                                    http_response_code('401');
                                    $arrJson = array(
                                        'http' => 401,
                                        'message' => 'error'
                                    );
                                } else {
                                    if ($this->Projects->save($edit)) {
                                        $arrJson = array(
                                            'http' => 200,
                                            'message' => 'success'
                                        );
                                    }
                                }
                                die(json_encode($arrJson));
                            }
                            die(json_encode($arrJson));
                        } elseif ($projects['status'] == PROJECT_STATUS_PENDDING) {// status = 2
                            if (!empty($projects->errors())) {
                                http_response_code('401');
                                $arrJson = array(
                                    'http' => 401,
                                    'message' => 'error'
                                );
                                die(json_encode($arrJson));
                            }
                            if ($end_date == NULL) {
                                if ($this->Projects->save($edit)) {
                                    $arrJson = array(
                                        'http' => 200,
                                        'message' => 'success'
                                    );
                                }
                            } else {
                                if ($projects->start_date > $projects->end_date) {
                                    http_response_code('401');
                                    $arrJson = array(
                                        'http' => 401,
                                        'message' => 'error'
                                    );
                                } else {
                                    if ($this->Projects->save($edit)) {
                                        $arrJson = array(
                                            'http' => 200,
                                            'message' => 'success'
                                        );
                                    }
                                }
                                die(json_encode($arrJson));
                            }
                            die(json_encode($arrJson));
                        }
                    }
                    if ($param['status'] == PROJECT_STATUS_DONE) {
                        if ($this->Projects->save($edit)) {
                            $arrJson = array(
                                'http' => 200,
                                'message' => 'success'
                            );
                            die(json_encode($arrJson));
                        }
                    }
                } else {
                    http_response_code('401');
                    $arrJson = array(
                        'http' => 401,
                        'message' => 'error',
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

}
