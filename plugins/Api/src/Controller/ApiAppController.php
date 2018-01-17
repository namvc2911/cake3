<?php

namespace Api\Controller;

use Cake\Controller\Controller as BaseController;
use Cake\Event\Event;
use Cake\Utility\Security;

class ApiAppController extends BaseController {

    public function beforeFilter(Event $event) {

        parent::beforeFilter($event);

        $users = $this->request->params;
        if ($users['controller'] != 'Users' && $users['action'] != 'login') {

//            Truyen bien param
            $param = $this->request->getData();
            $user_id = isset($param['user_id']) ? $param['user_id'] : '';
            $token = isset($param['token']) ? $param['token'] : '';
            if (!empty($user_id) && !empty($token)) {
//           Check Token
                $body = urldecode($token);
                $convert_string = base64_decode($body);

                $user_token = Security::decrypt($convert_string, TOKEN_KEY);

                $convert_again = json_decode($user_token);
                if (!empty($convert_again)) {
                    $userID = $convert_again->user_id;
                    $time_life = $convert_again->create_at;
                    $roleID = $convert_again->role_id;

                    $time = date('Y-m-d H:i:s');
                    $time_now = strtotime($time);

                    if ($user_id != $userID || $time_life < $time_now) {
                        http_response_code('404');
                        $arrJson = array(
                            'http' => 404,
                            'message' => 'not found'
                        );
                        die(json_encode($arrJson));
                    } else {
                        $this->set('roleID', $roleID);
                    }
                } else {
                    http_response_code('404');
                    $arrJson = array(
                        'http' => 404,
                        'message' => 'not found'
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

}
