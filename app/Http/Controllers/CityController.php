<?php

namespace App\Http\Controllers;

use App\Repositories\CityRepositoryInterface;
use Illuminate\Http\Request;

use App\Http\Requests;

class CityController extends Controller
{
    private $cityRepository;

    public function __construct(CityRepositoryInterface $cityRepository) {
        $this -> cityRepository = $cityRepository;
    }

    public function getcities(Request $request) {
        $phrase = $request -> phrase;
        $latitude = $request -> latitude;
        $longitude = $request -> longitude;

        $cities = $this -> cityRepository -> getByPhrase( $this -> eliminatePolishCharacters( $phrase ) );

        $return = array();

        foreach ($cities as $city) {
            array_push($return, array(
                'id' => $city -> id,
                'name' => $city -> name,
                'distance' => round( $this ->calculateDistance($latitude, $longitude, $city->latitude, $city->longitude) ),
            ));
        }

        usort($return, function($a, $b){ return $a['distance'] > $b['distance']; });
        return response() -> json(array(
            'cities' => $return,
        ));

    }
}
