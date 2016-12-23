<?php

namespace App\Http\Controllers;

use App\Repositories\CommentLikeNotificationRepositoryInterface;
use App\Repositories\CommentLikeRepositoryInterface;
use App\Repositories\CommentNotificationRepositoryInterface;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\FriendsPairRepositoryInterface;
use App\Repositories\LikeNotificationRepositoryInterface;
use App\Repositories\LikeRepositoryInterface;
use App\Repositories\NotificationRepositoryInterface;
use App\Repositories\PhotoRepositoryInterface;
use App\Repositories\PlaceRepositoryInterface;
use App\Repositories\TripCommentRepositoryInterface;
use App\Repositories\TripInvitationNotificationRepositoryInterface;
use App\Repositories\TripPlaceRepositoryInterface;
use App\Repositories\TripRepositoryInterface;
use App\Repositories\TripUserRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    private $tripRepository,
            $friendsPairRepository,
            $tripPlaceRepository,
            $tripUserRepository,
            $tripCommentRepository,
            $commentRepository,
            $commentLikeRepository,
            $likeRepository,
            $commentNotificationRepository,
            $commentLikeNotificationRepository,
            $likeNotificationRepository,
            $notificationRepository,
            $tripInvitationNotificationRepository,
            $placeRepository,
            $userRepository,
            $photoRepository;

    /**
     * TripController constructor.
     * @param TripRepositoryInterface $tripRepository
     * @param FriendsPairRepositoryInterface $friendsPairRepository
     * @param TripPlaceRepositoryInterface $tripPlaceRepository
     * @param TripUserRepositoryInterface $tripUserRepository
     * @param TripCommentRepositoryInterface $tripCommentRepository
     * @param CommentRepositoryInterface $commentRepository
     * @param CommentLikeRepositoryInterface $commentLikeRepository
     * @param LikeRepositoryInterface $likeRepository
     * @param CommentNotificationRepositoryInterface $commentNotificationRepository
     * @param CommentLikeNotificationRepositoryInterface $commentLikeNotificationRepository
     * @param LikeNotificationRepositoryInterface $likeNotificationRepository
     * @param NotificationRepositoryInterface $notificationRepository
     * @param TripInvitationNotificationRepositoryInterface $tripInvitationNotificationRepository
     * @param PlaceRepositoryInterface $placeRepository
     * @param UserRepositoryInterface $userRepository
     * @param PhotoRepositoryInterface $photoRepository
     */
    public function __construct(TripRepositoryInterface $tripRepository,
                                FriendsPairRepositoryInterface $friendsPairRepository,
                                TripPlaceRepositoryInterface $tripPlaceRepository,
                                TripUserRepositoryInterface $tripUserRepository,
                                TripCommentRepositoryInterface $tripCommentRepository,
                                CommentRepositoryInterface $commentRepository,
                                CommentLikeRepositoryInterface $commentLikeRepository,
                                LikeRepositoryInterface $likeRepository,
                                CommentNotificationRepositoryInterface $commentNotificationRepository,
                                CommentLikeNotificationRepositoryInterface $commentLikeNotificationRepository,
                                LikeNotificationRepositoryInterface $likeNotificationRepository,
                                NotificationRepositoryInterface $notificationRepository,
                                TripInvitationNotificationRepositoryInterface $tripInvitationNotificationRepository,
                                PlaceRepositoryInterface $placeRepository,
                                UserRepositoryInterface $userRepository,
                                PhotoRepositoryInterface $photoRepository)
    {
        $this -> tripRepository = $tripRepository;
        $this -> friendsPairRepository = $friendsPairRepository;
        $this -> tripPlaceRepository = $tripPlaceRepository;
        $this -> tripUserRepository = $tripUserRepository;
        $this -> tripCommentRepository = $tripCommentRepository;
        $this -> commentRepository = $commentRepository;
        $this -> commentLikeRepository = $commentLikeRepository;
        $this -> likeRepository = $likeRepository;
        $this -> commentNotificationRepository = $commentNotificationRepository;
        $this -> commentLikeNotificationRepository = $commentLikeNotificationRepository;
        $this -> likeNotificationRepository = $likeNotificationRepository;
        $this -> notificationRepository = $notificationRepository;
        $this -> tripInvitationNotificationRepository = $tripInvitationNotificationRepository;
        $this -> placeRepository = $placeRepository;
        $this -> userRepository = $userRepository;
        $this -> photoRepository = $photoRepository;
    }

    public function index($slug)
    {
        if(!isset($slug))
        {
            return back();
        }

        $trip = $this -> tripRepository -> getBySlug($slug);

        if(!isset($trip))
        {
            return back();
        }

        $tripUsers = $this -> tripUserRepository -> getByTripId($trip -> id);
        $users = [];
        foreach ($tripUsers as $tripUser)
        {
            $user = $this -> userRepository -> getById($tripUser -> user_id);
            $users[] = [
                'id' => $user -> id,
                'name' => $user -> first_name . ' ' . $user -> last_name,
                'url' => asset('user/' . $user -> username . '#/board'),
                'avatar' => asset($this -> photoRepository -> getById($user -> avatar_photo_id) -> thumb_url),
                'status' => $tripUser -> status,
            ];
        }

        $tripPlaces = $this -> tripPlaceRepository -> getByTripId($trip -> id);
        $places = [];
        $places[] = [
            'start'     => $trip -> start_time,
            'name'      => $trip -> start_address,
            'latitude'  => $trip -> start_latitude,
            'longitude' => $trip -> start_longitude,
            'position'  => 1,
        ];
        foreach ($tripPlaces as $tripPlace)
        {
            $place = $this -> placeRepository -> getById($tripPlace->place_id);
            $places[] = [
                'id'                => $place -> id,
                'start'             => $tripPlace -> start,
                'end'               => $tripPlace -> end,
                'name'              => $place -> name,
                'url'               => asset('places/' . $place -> slug),
                'short_description' => $place -> short_description,
                'latitude'          => $place -> latitude,
                'longitude'         => $place -> longitude,
                'position'  => 2,
            ];
        }
        $places[] = [
            'start'     => $trip -> end_time,
            'name'      => $trip -> end_address,
            'latitude'  => $trip -> end_latitude,
            'longitude' => $trip -> end_longitude,
            'position'  => 3,
        ];
//        $places = json_encode($places);

        return view('trips.index', compact('trip', 'users', 'places'));
    }

    /**
     * Zwraca formularz do tworzenia lub edytowania wycieczki
     *
     * @param null $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function form($slug = null)
    {
        if(!$slug)
        {
            return view('trips.form');
        }

        $trip = $this -> tripRepository -> getBySlug($slug);

        if(Auth::user()->id != $trip->user_id){
            return back();
        }

        $tripPlaces = $this -> tripPlaceRepository -> getByTripId($trip -> id);
        $tripUsers = $this -> tripUserRepository -> getByTripId($trip -> id);

        $sameAddress = $this -> sameAddress($trip);

        $tripJSON = [
            'id' => $trip -> id,
            'errors' => false,
            'name' => $trip -> name,
            'description' => $trip -> description,
            'start_date' => $trip -> start_time,
            'end_date' => $trip -> end_time,
            'start_address' => $trip -> start_address,
            'end_address' => $sameAddress ? null : $trip -> end_address,
            'start_marker' => [
                'coords' => [
                    'latitude' => (int)$trip -> start_latitude,
                    'longitude' => (int)$trip -> start_longitude,
                ],
            ],
            'end_marker' => $sameAddress ? null : [
                'coords' => [
                    'latitude' => (double)$trip -> end_latitude,
                    'longitude' => (double)$trip -> end_longitude,
                ],
            ],
            'same_address' => $sameAddress,

            'places' => [],
            'users' => [],
        ];

        foreach ($tripPlaces as $tripPlace)
        {
            $place = $this -> placeRepository -> getById($tripPlace -> place_id);
            $tripJSON['places'][] = [
                'id' => $place -> id,
                'name' => $place -> name,
                'start' => $tripPlace -> start,
                'end' => $tripPlace -> end,
            ];
        }

        foreach ($tripUsers as $tripUser)
        {
            $user = $this -> userRepository -> getById($tripUser -> user_id);
            $tripJSON['users'][] = [
                'id' => $user -> id,
                'first_name' => $user -> first_name,
                'last_name' => $user -> last_name,
                'thumb_url' => $user -> avatar_photo_id ? asset($this -> photoRepository -> getById($user -> avatar_photo_id) -> thumb_url) : asset('images/avatar_min_' . $user -> sex . '.png'),
                'deletable' => false,
            ];
        }

        $tripJSON = json_encode($tripJSON);

        return view('trips.form', compact('trip', 'tripJSON'));
    }

    /**
     * Zwraca prawdę jeźli adresy początku i końca wycieczki są takie same
     *
     * @param Trip $trip
     * @return bool
     */
    private function sameAddress(Trip $trip)
    {
        return $trip -> start_latitude == $trip -> end_latitude && $trip -> start_longitude == $trip -> end_longitude && $trip -> start_address == $trip -> end_address;
    }

    /**
     * Sprawdza czy wycieczka o podanym slugu już nie istnieje
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exists(Request $request)
    {
        $slug = $request -> slug;
        $trip = $this -> tripRepository -> getBySlug($slug);
        return response() -> json(
            [
                'exists' => isset($trip),
            ]
        );
    }

    /**
     * Wyszukuje znajomych
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFriendsByPhrase(Request $request)
    {
        $user_id = $request -> user_id;
        $phrase = $request -> phrase;

        $friends = $this -> friendsPairRepository -> getFriendsByPhrase($user_id, $phrase);

        foreach ($friends as &$friend)
            $friend -> thumb_url = asset($friend -> thumb_url);

        return response() -> json([
            'friends' => $friends
        ]);
    }

    /**
     * Tworzy nową wycieczkę
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $data = $request -> all();

        $trip = $this -> tripRepository -> create([
            'name' => $data['name'],
            'description' => $data['description'],
            'slug' => $data['slug'],
            'user_id' => $data['user_id'],

            'start_time' => new Carbon($data['start_date']),

            'start_address' => $data['start_address'],
            'start_latitude' => $data['start_marker']['coords']['latitude'],
            'start_longitude' => $data['start_marker']['coords']['longitude'],

            'end_time' => new Carbon($data['end_date']),

            'end_address' => ($data['same_address'] ? $data['start_address'] : $data['end_address']),
            'end_latitude' => ($data['same_address'] ? $data['start_marker']['coords']['latitude'] : $data['end_marker']['coords']['latitude']),
            'end_longitude' => ($data['same_address'] ? $data['start_marker']['coords']['longitude'] : $data['end_marker']['coords']['longitude']),
        ]);

        if( isset($trip) ) {
            foreach ($data['places'] as $place) {
                $this -> tripPlaceRepository -> create([
                    'trip_id' => $trip -> id,
                    'place_id' => $place['id'],
                    'start' => $place['start'],
                    'end' => $place['end'],
                ]);
            }

            foreach($data['users'] as $user){
                $tripUser = $this -> tripUserRepository -> create([
                    'trip_id' => $trip -> id,
                    'user_id' => $user['id'],
                ]);

                $notification_id = $this -> notificationRepository -> create($user['id'], 'trip-invitation');
                $this -> tripInvitationNotificationRepository -> create($tripUser->id, $notification_id);
            }

            $this -> tripUserRepository -> create([
                'trip_id' => $trip -> id,
                'user_id' => $data['user_id'],
                'status'  => 1,
            ]);
        }

        return response() -> json([
            'success' => isset($trip)
        ]);
    }

    /**
     * Akceptuje zaproszenie na wycieczkę
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function accept(Request $request)
    {
        $trip_user_id = $request -> trip_user_id;
        $trip = $this -> tripUserRepository -> update($trip_user_id, ['status' => 1]);

        $tripInvitationNotification = $this -> tripInvitationNotificationRepository -> getByTripUserId($trip_user_id);
        $this -> notificationRepository -> delete($tripInvitationNotification -> notification_id);
        $this -> tripInvitationNotificationRepository -> delete($tripInvitationNotification -> id);

        return response() -> json(['success' => isset($trip)]);
    }

    /**
     * Odrzuca i jednocześnie usuwa zaproszenie na wycieczkę
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function decline(Request $request)
    {
        $trip_user_id = $request -> trip_user_id;

        $tripInvitationNotification = $this -> tripInvitationNotificationRepository -> getByTripUserId($trip_user_id);
        $this -> notificationRepository -> delete($tripInvitationNotification -> notification_id);
        $this -> tripInvitationNotificationRepository -> delete($tripInvitationNotification -> id);

        return response() -> json(['success' => $this -> tripUserRepository -> delete($trip_user_id)]);
    }

    /**
     * Zwraca wycieczki stworzone przez użytkownika
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserTrips(Request $request)
    {
        $trips = $this -> tripRepository -> getByUserId($request -> user_id);

        $trips_response = [];
        foreach ($trips as $trip)
        {
            $trips_response[] = [
                'id' => $trip -> id,
                'name' => $trip -> name,
                'link' => asset('trips/' . $trip->slug),
                'edit_link' => asset('trips/edit/' . $trip->slug),
                'start' => $trip -> start_time,
                'end' => $trip -> end_time,
                'is_owner' => Auth::user()->id == $request -> user_id,
            ];
        }

        return response() -> json(['trips' => $trips_response]);
    }

    public function update(Request $request)
    {
        $data = $request -> all();

        $trip = $this -> tripRepository -> update($data['id'], [
            'description' => $data['description'],

            'start_time' => new Carbon($data['start_date']),

            'start_address' => $data['start_address'],
            'start_latitude' => $data['start_marker']['coords']['latitude'],
            'start_longitude' => $data['start_marker']['coords']['longitude'],

            'end_time' => new Carbon($data['end_date']),

            'end_address' => ($data['same_address'] ? $data['start_address'] : $data['end_address']),
            'end_latitude' => ($data['same_address'] ? $data['start_marker']['coords']['latitude'] : $data['end_marker']['coords']['latitude']),
            'end_longitude' => ($data['same_address'] ? $data['start_marker']['coords']['longitude'] : $data['end_marker']['coords']['longitude']),
        ]);

        if( isset($trip) ) {

            $tripPlaces = $this -> tripPlaceRepository -> getByTripId($trip->id);
            foreach ($tripPlaces as $tripPlace){
                $this -> tripPlaceRepository -> delete($tripPlace->id);
            }

            foreach ($data['places'] as $place) {
                $this -> tripPlaceRepository -> create([
                    'trip_id' => $trip -> id,
                    'place_id' => $place['id'],
                    'start' => $place['start'],
                    'end' => $place['end'],
                ]);
            }

            foreach($data['users'] as $user){

                if($user['deletable']){
                    $tripUser = $this -> tripUserRepository -> create([
                        'trip_id' => $trip -> id,
                        'user_id' => $user['id'],
                    ]);

                    $notification_id = $this -> notificationRepository -> create($user['id'], 'trip-invitation');
                    $this -> tripInvitationNotificationRepository -> create($tripUser->id, $notification_id);
                }

            }
        }

        return response() -> json([
            'success' => isset($trip)
        ]);
    }

    /**
     * Usuwa wycieczkę i wszystkie jej elementy
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $trip = $this -> tripRepository -> get($request -> id); // wycieczka

        $tripUsers = $this -> tripUserRepository -> getByTripId($trip -> id); // uczestnicy wycieczki
        foreach ($tripUsers as $tripUser) {
            $tripInvitationNotification = $this -> tripInvitationNotificationRepository -> getByTripUserId($tripUser -> id); // zaproszenie na wycieczkę
            if(isset($tripInvitationNotification)){
                $this -> notificationRepository -> delete($tripInvitationNotification -> notification_id);
                $this -> tripInvitationNotificationRepository -> delete($tripInvitationNotification -> id);
            }
            $this -> tripUserRepository -> delete($tripUser -> id);
        }

        $tripPlaces = $this -> tripPlaceRepository -> getByTripId($trip -> id);
        foreach ($tripPlaces as $tripPlace) {
            $this -> tripPlaceRepository -> delete($tripPlace -> id);
        }

        $tripComments = $this -> tripCommentRepository -> getByTripId($trip -> id);
        foreach ($tripComments as $tripComment) {
            $comment = $this -> commentRepository -> getById($tripComment -> comment_id);

            $this -> tripCommentRepository -> delete($this -> tripCommentRepository -> getByCommentId($comment -> id) -> id);

            $commentLikes = $this -> commentLikeRepository -> getByCommentId($comment -> id); // wszystkie lajki tego komentarza
            foreach ($commentLikes as $commentLike){
                $this -> likeRepository -> delete($commentLike -> like_id); // usuń lajka
                $this -> commentLikeRepository -> delete($commentLike->id); // usuń lajka komentarza
            }

            $commentLikeNotifications = $this -> commentLikeNotificationRepository -> getByCommentId($comment -> id); // notyfikacje odnośnie lajków tego komentarza
            foreach ($commentLikeNotifications as $commentLikeNotification){
                $likeNotification = $this -> likeNotificationRepository -> get($commentLikeNotification->like_notification_id); // notyfikacja polubienia
                $this -> notificationRepository -> delete($likeNotification -> notification_id); // usuń notyfikację
                $this -> likeNotificationRepository -> delete($likeNotification -> id); // usuń notyfikację polubienia
                $this -> commentLikeNotificationRepository -> delete($commentLikeNotification->id); // usuń notyfikację polubienia komentarza
            }

            $commentNotifications = $this -> commentNotificationRepository -> getByCommentId($comment -> id); // notyfikacje odnośnie komentowania
            foreach ($commentNotifications as $commentNotification){
                $this -> notificationRepository -> delete($commentNotification -> notification_id);
                $this -> commentNotificationRepository -> delete($commentNotification -> id);
            }

            $this -> commentRepository -> delete($comment->id);
            $this -> tripCommentRepository -> delete($tripComment -> id);
        }

        return response() -> json(['success' => $this -> tripRepository -> delete($trip -> id)]);
    }
}
