<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Cake\Event\Event;
use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Http\ServerRequest;
use App\Utility\SecurityMcrypt;

class OAuthController extends AppController {

    public function initialize() {
        parent::initialize();

        $this->Auth->allow(['authen']);
    }

    public function authen() {
        $param = $this->request->getQueryParams();
        $user_id = isset($param['user_id']) ? $param['user_id'] : '';
        $token = isset($param['token']) ? $param['token'] : '';
        if (!empty($token)) {
//           Check Token
            $convert_string = base64_decode($token);
            $user_token = SecurityMcrypt::decrypt($convert_string, TOKEN_KEY);
            $convert_again = json_decode($user_token);
            if (!empty($convert_again)) {
                $userID = $convert_again->user_id;
                $url_redirect = $convert_again->url_redirect;

                if ($user_id == $userID) {
                    $this->request->session()->destroy();
                    $users = $this->loadModel('Users');
                    $query = $users->find()
                            ->select([
                                'Users.user_id',
                                'Users.username',
                                'Users.fullname',
                                'Users.nickname',
                                'role_id' => 'role_mst.role_id',
                                'Users.create_time',
                                'Users.update_time',
                                'roleID' => 'role_mst.value'
                            ])->join([
                                'table' => 'role_mst',
                                'type' => 'LEFT',
                                'conditions' => 'Users.role_id = role_mst.role_id'
                            ])
                            ->where(['Users.user_id' => $userID])
                            ->first();

                    if (!empty($query)) {
                        $roleID = $query['roleID'];
                        $user = [];
                        $user['user_id'] = $query['user_id'];
                        $user['username'] = $query['username'];
                        $user['fullname'] = $query['fullname'];
                        $user['nickname'] = $query['nickname'];
                        $user['role_id'] = $query['role_id'];
                        $user['create_time'] = $query['create_time'];
                        $user['update_time'] = $query['update_time'];
                        if ($user) {
                            $user['role'] = $roleID;
                            $this->Auth->setUser($user);
                            if ($url_redirect == '') {

                                return $this->redirect('/');
                            }
                            return $this->redirect($url_redirect);
                        }
                    }
                    return $this->redirect('/dang-nhap');
                }
                return $this->redirect('/dang-nhap');
            }

            return $this->redirect('/dang-nhap');
        }
        return $this->redirect('/dang-nhap');
    }

}
