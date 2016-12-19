<?php

namespace App\Http\Controllers;

use App\Repositories\FriendsPairRepositoryInterface;
use App\Repositories\TripRepositoryInterface;
use Illuminate\Http\Request;

use App\Http\Requests;

class TripController extends Controller
{
    private $tripRepository, $friendsPairRepository;

    public function __construct(TripRepositoryInterface $tripRepository, FriendsPairRepositoryInterface $friendsPairRepository)
    {
        $this -> tripRepository = $tripRepository;
        $this -> friendsPairRepository = $friendsPairRepository;
    }

    public function form($slug = null)
    {
        if(!$slug)
        {
            return view('trips.form');
        }
    }

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
}
