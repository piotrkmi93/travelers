<?php
/**
 * Created by PhpStorm.
 * User: Piotr Kmiecik
 * Date: 19.12.2016
 * Time: 23:01
 */

namespace App\Repositories;


interface TripInvitationNotificationRepositoryInterface
{
    public function create($trip_user_id, $notification_id);
    public function delete($id);
    public function get($id);
    public function getByTripUserId($trip_user_id);
    public function getByNotificationId($notification_id);
}