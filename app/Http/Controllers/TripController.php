<?php

namespace App\Http\Controllers;

use App\Repositories\TripRepositoryInterface;
use Illuminate\Http\Request;

use App\Http\Requests;

class TripController extends Controller
{
    private $tripRepository;

    public function __construct(TripRepositoryInterface $tripRepository)
    {
        $this -> tripRepository = $tripRepository;
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
}
