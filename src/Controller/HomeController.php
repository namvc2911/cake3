<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Cake\Event\Event;
use App\Controller\AppController;

/**
 * Description of HomeController
 *
 * @author manht
 */
class HomeController extends AppController {

    //put your code here

    public function initialize() {
        parent::initialize();
        $this->viewBuilder()->setLayout('my_layout');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
    }

    public function index() {
        
        $this->loadModel('Projects');

        $this->loadModel('MemberProject');
        $totalProjectUser = $this->MemberProject->find()
                ->where([
                    'user_id' => $this->Auth->user()['user_id'],
                ])
                ->contain(['Projects' => function ($q) {
                        return $q->where(['status IN' => ['1', '2', '3']]);
                    }])
                ->count();
        $completedProjectUser = $this->MemberProject->find()
                ->where([
                    'user_id' => $this->Auth->user()['user_id'],
                ])
                ->contain(['Projects' => function ($q) {
                        return $q->where(['status' => 3]);
                    }])
                ->count();
        $completingProjectUser = $this->MemberProject->find()
                ->where([
                    'user_id' => $this->Auth->user()['user_id'],
                ])
                ->contain(['Projects' => function ($q) {
                        return $q->where(['status' => 2]);
                    }])
                ->count();
        $uncompleteProjectUser = $this->MemberProject->find()
                ->where([
                    'user_id' => $this->Auth->user()['user_id'],
                ])
                ->contain(['Projects' => function ($q) {
                        return $q->where(['status' => 1]);
                    }])
                ->count();
        $completedProject = $this->Projects->find()
                ->where([
                    'status' => '3'
                ])
                ->count();
        $completingProject = $this->Projects->find()
                ->where([
                    'status' => '2'
                ])
                ->count();
        $uncompleteProject = $this->Projects->find()
                ->where([
                    'status' => '1'
                ])
                ->count();
        $totalProject = $this->Projects->find()->count();
        $this->set(compact('totalProject','completedProject','completingProject','uncompleteProject',
                'totalProjectUser','completedProjectUser','completingProjectUser','uncompleteProjectUser'));
    }

}
