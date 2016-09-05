<?php

namespace App\Repositories\Eloquent;
use App\InvitationNotification;
use App\Repositories\InvitationNotificationRepositoryInterface;

class InvitationNotificationRepository implements InvitationNotificationRepositoryInterface {
    private $model;

    public function __construct(InvitationNotification $invitationNotification){
        $this -> model = $invitationNotification;
    }

    /**
     * @param $friends_pair_id
     * @param $notification_id
     * @return mixed
     */
    public function create($friends_pair_id, $notification_id){
        $invitationNotification = new InvitationNotification();
        $invitationNotification -> friends_pair_id = $friends_pair_id;
        $invitationNotification -> notification_id = $notification_id;
        $invitationNotification -> save();
        return $invitationNotification -> id;
    }

    /**
     * @param $friends_pair_id
     * @return mixed
     */
    public function delete($friends_pair_id){
        $invitationNotification = $this->model->where('friends_pair_id', '=', $friends_pair_id)->first();
        $notification_id = $invitationNotification->notification_id;
        return $notification_id;
    }

    /**
     * @param $notification_id
     * @return mixed
     */
    public function getByNotificationId($notification_id){
        return $this->model->where('notification_id', '=', $notification_id)->first();
    }
}