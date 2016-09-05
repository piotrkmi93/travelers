<?php

namespace App\Repositories\Eloquent;
use App\Notification;
use App\Repositories\NotificationRepositoryInterface;


class NotificationRepository implements NotificationRepositoryInterface {

    private $model;

    /**
     * NotificationRepository constructor.
     * @param Notification $model
     */
    public function __construct(Notification $model){
        $this -> model = $model;
    }

    /**
     * @param $user_id
     * @param $type
     * @return mixed
     */
    public function create($user_id, $type){
        $notification = new Notification();
        $notification -> user_id = $user_id;
        $notification -> type = $type;
        $notification -> save();
        return $notification -> id;
    }

    public function delete($id){
        return $this -> model -> find($id) -> delete();
    }

    /**
     * @param $user_id
     * @param $last_id
     * @return mixed
     */
    public function getNotifications($user_id, $last_id) {
        $notifications = $this -> model -> where('user_id', '=', $user_id);
        if ($last_id) $notifications = $notifications -> where('id', '>', $last_id);
        return $notifications -> get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this -> model -> find($id);
    }

}