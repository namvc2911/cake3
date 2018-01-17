<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

/**
 * Description of ManagerOvertimesController
 *
 * @author manht
 */
class OvertimesTable extends Table {

    //put your code here

    public function initialize(array $config) {
        $this->setTable('manage_overtime_tbl');

        $this->belongsTo('Projects', [
            'targetForeignKey' => 'project_id',
            'joinTable' => 'project_tbl',
            'foreignKey' => 'project_id',
        ]);
        $this->belongsTo('Users', [
            'targetForeignKey' => 'user_id',
            'joinTable' => 'user_tbl',
            'foreignKey' => 'user_id',
        ]);

        $this->hasMany('Members')
                ->setForeignKey('project_id')
                ->setDependent(true);
    }

    // Caculate point overtime to export CSV
    public function getOvertimePoint($user_id, $month, $year) {
        $arrDayOfMonth = [];
        $sumPoint = 0;
        $dayOfMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($i = 1; $i <= $dayOfMonth; $i++) {
            $d = new Time($year . '-' . $month . '-' . $i);
            $point = "";

            $query = $this->find()->where([
                        'user_id' => $user_id,
                        'day_of_overtime' => $d->format('Y-m-d'),
                        'delete_status' => 0,
                        'approve_status' => 1,
                    ])->toArray();

            if ($query) {

                foreach ($query as $result) {
                    $num = ($this->convertMinute($result["end_at"]) - $this->convertMinute($result["start_at"]) - $result["breaktime"]) / 60;
                    $point += $this->fractionRound($num, 0.25);
                }
                $sumPoint += $point;
            }
            $arrDayOfMonth[$i] = $point;
        }
        //Sum point / user
        $arrDayOfMonth[$dayOfMonth + 1] = $sumPoint;
        return $arrDayOfMonth;
    }

    //Convert HH:mm to minutes

    private function convertMinute($time) {
        sscanf($time, "%d:%d", $hours, $minutes);

        return $hours * 60 + $minutes;
    }

    //Round up 0.25 to convert point overtime
    private function fractionRound($num, $frac) {
        return ceil($num / $frac) * $frac;
    }

    public function validationSearch(Validator $validator) {
        return $validator
                        ->add('month', 'inList', [
                            'rule' => ['inList', ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12']],
                            'message' => 'Sai tháng'
                        ])
                        ->notEmpty('year', 'Trường này rỗng')
                        ->add('year', 'date', [
                            'rule' => ['date', 'y'],
                            'message' => 'Sai năm'
        ]);
    }

    public function validationAdd(Validator $validator) {
        return $validator
                        ->notEmpty('day_of_overtime', 'Hãy nhập ngày làm thêm giờ')
                        ->notEmpty('reason', 'Hãy nhập lí do')
                        ->notEmpty('project_id', 'Hãy nhập dự án')
                        ->notEmpty('start_at', 'Hãy nhập thời gian bắt đầu')
                        ->notEmpty('end_at', 'Hãy nhập thời gian kết thúc')
                        ->add('start_at', 'custom', [
                            'rule' => function ($value, $context) {
                                $query = $this->find()->where([
                                    'user_id' => $context['data']['user_id'],
                                    'day_of_overtime' => date("Y-m-d", strtotime($context['data']['day_of_overtime'])),
                                    'start_at <=' => $context['data']['end_at'],
                                    'end_at >=' => $context['data']['start_at'],
                                    'manage_overtime_id !=' => $context['data']['manage_overtime_id'],
                                   'delete_status' => 0,
                                ]);
                                return (!$query->toArray());
                            }, 'message' => 'Đơn làm thêm bị trùng thời gian'
                        ])
                        ->add('end_at', 'custom', [
                            'rule' => function ($value, $context) {
                                $query = $this->find()->where([
                                    'user_id' => $context['data']['user_id'],
                                    'day_of_overtime' => date("Y-m-d", strtotime($context['data']['day_of_overtime'])),
                                    'start_at <=' => $context['data']['end_at'],
                                    'end_at >=' => $context['data']['start_at'],
                                    'manage_overtime_id !=' => $context['data']['manage_overtime_id'],
                                    'delete_status' => 0,
                                ]);
                                return (!$query->toArray());
                            }, 'message' => 'Đơn làm thêm bị trùng thời gian'
                        ])
                        ->add('day_of_overtime', 'custom', [
                            'rule' => function ($value, $context) {
                                $project = TableRegistry::get('Projects');
                                $query = $project->find()->select(['start_date', 'end_date'])->where([
                                            'project_id' => $context['data']['project_id']
                                        ])->first();

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
                            }, 'message' => 'Thành viên không thuộc dự án'])
        ;
    }

    public function validationDeny(Validator $validator) {
        return $validator->notEmpty('deny_reason', 'Nhập lý do từ chối');
    }

}
