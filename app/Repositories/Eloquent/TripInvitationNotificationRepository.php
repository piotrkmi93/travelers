<?php
/**
 * Created by PhpStorm.
 * User: Piotr Kmiecik
 * Date: 19.12.2016
 * Time: 23:02
 */

namespace App\Repositories\Eloquent;


use App\Repositories\TripInvitationNotificationRepositoryInterface;
use App\TripInvitationNotification;

class TripInvitationNotificationRepository implements TripInvitationNotificationRepositoryInterface
{
    /**
     * @var TripInvitationNotification
     */
    private $model;

    /**
     * TripInvitationNotificationRepository constructor.
     * @param TripInvitationNotification $model
     */
    public function __construct(TripInvitationNotification $model)
    {
        $this -> model = $model;
    }

    /**
     * @param int $trip_user_id
     * @param int $notification_id
     * @return null|TripInvitationNotification
     */
    public function create($trip_user_id, $notification_id)
    {
        $tin = $this -> model -> newInstance();
        $tin -> trip_user_id = $trip_user_id;
        $tin -> notification_id = $notification_id;
        return $tin -> save() ? $tin : null;
    }

    /**
     * @param int $id
     * @return bool|null
     */
    public function delete($id)
    {
        return $this -> get($id) -> delete();
    }

    /**
     * @param int $id
     * @return TripInvitationNotification|null
     */
    public function get($id)
    {
        return $this -> model -> find($id);
    }

    /**
     * @param int $trip_user_id
     * @return array
     */
    public function getByTripUserId($trip_user_id)
    {
        return $this -> model -> whereTripUserId($trip_user_id) -> first();
    }

    /**
     * @param int $notification_id
     * @return array
     */
    public function getByNotificationId($notification_id)
    {
        return $this -> model -> whereNotificationId($notification_id) -> first();
    }


}