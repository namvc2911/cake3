<?php

namespace Api\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Validation\Validator;

class OvertimesTable extends Table {

    public function initialize(array $config) {
        $this->table('manage_overtime_tbl');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'create_time' => 'new',
                    'update_time' => 'always',
                ]
            ]
        ]);
    }

    public function getformatDate() {
        $query = $this->find();
        $day_of_overtime = $query->func()->date_format([
            'Overtimes.day_of_overtime' => 'identifier',
            "'%Y-%m-%d'" => 'literal'
        ]);
        return $day_of_overtime;
    }

    public function validationDefault(Validator $validator) {
        $validator
                ->notEmpty('user_id')
                ->notEmpty('member_id')
                ->notEmpty('project_id')
                ->notEmpty('day_of_overtime')
                ->notEmpty('start_at')
                ->notEmpty('end_at')
                ->notEmpty('breaktime')
                ->add('day_of_overtime', [
                    'date' => [
                        'rule' => ['date']
                    ]
        ]);

        return $validator;
    }

    public function formatDayOvertime() {
        $query = $this->find();
        $day_of_overtime = $query->func()->date_format([
            'Overtimes.day_of_overtime' => 'identifier',
            "'%Y-%m-%d'" => 'literal'
        ]);
        return $day_of_overtime;
    }

    public function formatCreatedtime() {
        $query = $this->find();
        $created_time = $query->func()->date_format([
            'Overtimes.create_time' => 'identifier',
            "'%Y-%m-%d'" => 'literal'
        ]);
        return $created_time;
    }

    public function formatUpdatedtime() {
        $query = $this->find();
        $updated_time = $query->func()->date_format([
            'Overtimes.update_time' => 'identifier',
            "'%Y-%m-%d'" => 'literal'
        ]);
        return $updated_time;
    }

    public function formatStartAt() {
        $query = $this->find();
        $start_at = $query->func()->time_format([
            'Overtimes.start_at' => 'identifier',
            "'%H:%i'" => 'literal'
        ]);
        return $start_at;
    }

    public function formatEndAt() {
        $query = $this->find();
        $end_at = $query->func()->time_format([
            'Overtimes.end_at' => 'identifier',
            "'%H:%i'" => 'literal'
        ]);
        return $end_at;
    }

    //xem danh sách các ngày trong tháng: hiển thị ngày làm thêm, 
    //dự án nào, lí do, có được duyệt k, ai duyệt
    public function listAllDay($data = []) {

        $user_id = isset($data['user_id']) ? $data['user_id'] : '';

        if ($user_id == "") {
            return null;
        }

        $query = $this->find();
        $day_of_overtime = $this->formatDayOvertime();
        $start_at = $this->formatStartAt();
        $end_at = $this->formatEndAt();

        $query->select([
            'Overtimes.manage_overtime_id',
            'day_overtime' => $day_of_overtime,
            'Overtimes.project_id',
            'project_name' => 'Projects.name',
            'Overtimes.reason',
            'Overtimes.approve_status',
            'Overtimes.approver',
            'Overtimes.deny_reason',
            'start_at' => $start_at,
            'end_at' => $end_at,
            'Overtimes.breaktime',
            'approver_fullname' => 'Users.fullname'
        ])->where([
            'Overtimes.user_id' => $user_id,
            'Overtimes.delete_status' => NODELETE_STATUS
        ])->join([
            'table' => 'user_tbl',
            'alias' => 'Users',
            'type' => 'LEFT',
            'conditions' => 'Overtimes.approver = Users.user_id',
        ])->join([
            'table' => 'project_tbl',
            'alias' => 'Projects',
            'type' => 'LEFT',
            'conditions' => 'Projects.project_id = Overtimes.project_id',
        ]);

        $total = $query->count();

        return array(
            'http' => 200,
            'message' => 'success',
            'list_dayovertime' => $query,
            'total' => $total
        );
    }

    //danh sách các yêu cầu làm thêm giờ chưa được phê duyệt
    // của các dự án user tham gia với vai trò là leader	
    public function getListOvertime($data = []) {
        $user_id = isset($data['user_id']) ? $data['user_id'] : '';

        if ($user_id == "") {
            return null;
        }

        //gọi hàm check leader
        $check = $this->checkIsLeader($user_id);

        //điều kiện chưa được phê duyệt
        $checkApproved = $check->where(['Overtimes.approve_status' => MANAGE_STATUS_NOAPPROVED,
                                        'Overtimes.delete_status' => NODELETE_STATUS]);

        $total = $checkApproved->count();

        return array(
            'http' => 200,
            'message' => 'success',
            'days_overtime' => $checkApproved,
            'total' => $total
        );
    }

    //danh sách các yêu cầu làm thêm giờ đã được phê duyệt
    // của các dự án user tham gia với vai trò là leader
    public function getListOvertimeApproved($data = []) {

        $user_id = isset($data['user_id']) ? $data['user_id'] : '';

        if ($user_id == "") {
            return null;
        }

        //gọi hàm check leader
        $check = $this->checkIsLeader($user_id);

        //điều kiện đã được phê duyệt
        $checkApproved = $check->where(['Overtimes.approve_status' => MANAGE_STATUS_APPROVED,
                                        'Overtimes.approver'=>$user_id]);
        $total = $checkApproved->count();
        return array(
            'http' => 200,
            'message' => 'success',
            'days_overtime' => $checkApproved,
            'total' => $total
        );
    }

    //lay ra danh sach cac yeu cau lam them gio bi tu choi
    public function getListDeny($data = []) {
        $user_id = isset($data['user_id']) ? $data['user_id'] : '';

        if ($user_id == "") {
            return null;
        }
        //gọi hàm check leader
        $check = $this->checkIsLeader($user_id);

        //điều kiện bị từ chối
        $query = $check->where(['Overtimes.approve_status' => MANAGE_STATUS_DENY_APPROVED,
                                'Overtimes.approver'=>$user_id]);
        $total = $query->count();

        return array(
            'http' => 200,
            'message' => 'success',
            'days_overtime' => $query,
            'total' => $total
        );
    }

    //check user_id có là leader k?
    public function checkIsLeader($user_id) {
        $query = $this->find();
        $day_of_overtime = $this->formatDayOvertime();
        $created_time = $this->formatCreatedtime();
        $updated_time = $this->formatUpdatedtime();
        $start_at = $this->formatStartAt();
        $end_at = $this->formatEndAt();

        $query = $this->find()
                        ->select([
                            'Overtimes.manage_overtime_id',
                            'Overtimes.user_id',
                            'Overtimes.project_id',
                            'day_overtime' => $day_of_overtime,
                            'Overtimes.reason',
                            'Overtimes.approve_status',
                            'Overtimes.approver',
                            'Overtimes.deny_reason',
                            'start_at' => $start_at,
                            'end_at' => $end_at,
                            'Overtimes.breaktime',
                            'created_time' => $created_time,
                            'Overtimes.created_by',
                            'updated_time' => $updated_time,
                            'Overtimes.updated_by',
                            'approver_fullname' => 'Users.fullname'
                        ])->join([
                    'table' => 'member_project_tbl',
                    'alias' => 'Members',
                    'type' => 'LEFT',
                    'conditions' => 'Overtimes.project_id = Members.project_id '
                ])->join([
                    'table' => 'user_tbl',
                    'alias' => 'Users',
                    'type' => 'LEFT',
                    'conditions' => 'Overtimes.approver = Users.user_id',
                ])->where([
            'Members.user_id' => $user_id,
            'Members.is_leader' => MEMBER_LEADER
        ]);

        return $query;
    }

}

?>