<?php

namespace Api\Controller;

use Api\Controller\ApiAppController;
use Cake\Event\Event;
use Cake\Utility\Security;
use Cake\Auth\DefaultPasswordHasher;

class UsersController extends ApiAppController {

    public function login() {
        $this->autoRender = false;
        if ($this->request->is('post')) {

            $param = $this->request->getdata();
            $username = isset($param['username']) ? $param['username'] : '';
            $password = isset($param['password']) ? $param['password'] : '';

            if (empty($username) || empty($password)) {
                $arrJson = array(
                    'http' => 400,
                    'message' => 'Username hoac password khong duoc de trong'
                );
                die(json_encode($arrJson));
            }

            $query = $this->Users->find()->select([
                        'user_id',
                        'username',
                        'password',
                        'fullname',
                        'role_id' => 'role_mst.role_id',
                        'roleID' => 'role_mst.value',
                        'create_time',
                        'update_time',
                    ])->join([
                        'table' => 'role_mst',
                        'type' => 'LEFT',
                        'conditions' => 'Users.role_id = role_mst.role_id'
                    ])->where(['username' => $username])->first();
            $userID = $query['user_id'];
            $roleID = $query['roleID'];

            $pwd = $query['password'];
            $passHash = new DefaultPasswordHasher();
            $user_password = $passHash->check($password, $pwd);
            if (!empty($query) && $user_password == 1) {
                $token = array();
                $time = strtotime(date('Y-m-d H:i:s'));

                $token['username'] = $username;
                $token['user_id'] = $userID;
                $token['role_id'] = $roleID;
                $token['create_at'] = $time + time_life;

                $convert_string = json_encode($token);

                $user_token = Security::encrypt($convert_string, TOKEN_KEY);

                $body = base64_encode($user_token);
                $body_token= urlencode($body);

                unset($query['password']);

                $arrJson = array(
                    'http' => 200,
                    'message' => 'success',
                    'token' => $body_token,
                    'user-data' => $query
                );

                die(json_encode($arrJson));
            } else {
                http_response_code('400');
                $arrJson = array(
                    'http' => 400,
                    'message' => 'Username hoac password khong ton tai!'
                );
            }
            die(json_encode($arrJson));
        } else {
            http_response_code('400');
            $arrJson = array(
                'http' => 400,
                'message' => 'bad request',
            );
            die(json_encode($arrJson));
        }
    }

    public function logout() {
        $this->autoRender = false;

        if ($this->request->is('get')) {
            $this->request->session()->destroy();
            $arrJson = array(
                'http' => 200,
                'message' => 'success'
            );
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
