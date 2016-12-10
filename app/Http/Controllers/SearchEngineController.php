<?php

namespace App\Http\Controllers;

use App\Repositories\CityRepositoryInterface;
use App\Repositories\FriendsPairRepositoryInterface;
use App\Repositories\PlaceLikeRepositoryInterface;
use App\Repositories\PlaceRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

class SearchEngineController extends Controller
{
    private $userRepository, $friendPairRepository, $placeRepository, $placeLikeRepository, $cityRepository;

    public function __construct(UserRepositoryInterface $userRepository, FriendsPairRepositoryInterface $friendsPairRepository, PlaceRepositoryInterface $placeRepository, PlaceLikeRepositoryInterface $placeLikeRepository, CityRepositoryInterface $cityRepository)
    {
        $this -> userRepository = $userRepository;
        $this -> friendPairRepository = $friendsPairRepository;
        $this -> placeRepository = $placeRepository;
        $this -> placeLikeRepository = $placeLikeRepository;
        $this -> cityRepository = $cityRepository;
    }

    public function search($user_id, $phrase)
    {
        $users = $this -> userRepository -> searchByPhrase($phrase);
        $places = $this -> placeRepository -> getByPhrase($phrase);
        $users_response = [];
        $places_response = [];

        foreach ($users as $user)
        {
            $users_response[] = [
                'name' => $user -> first_name . ' ' . $user -> last_name,
                'link' => asset('user/' . $user -> username . '#/board'),
                'avatar' => asset(getThumb($user -> avatar_photo_id)),
                'is_friend' => $this -> friendPairRepository -> checkIsUserYourFriend($user_id, $user->id),
                'city' => $this -> cityRepository -> getById($user -> city_id) -> name,
                'is_you' => $user->id == $user_id,
                'is_active' => (new Carbon()) < (new Carbon($user -> active_to)),
                'sex' => $user -> sex,
            ];
        }

        foreach ($places as $place)
        {
            $places_response[] = [
                'name' => $place -> name,
                'place_type' => $place -> place_type,
                'is_liked' => $this -> placeLikeRepository -> exists($user_id, $place->id),
                'city' => $this -> cityRepository -> getById($place -> city_id) -> name,
                'link' => asset('places/' . $place->slug),
            ];
        }

        return response() -> json(['users' => $users_response, 'places' => $places_response]);
    }
}
