<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    /**
     * @param $phrase
     * @return mixed
     */
    protected function eliminatePolishCharacters($phrase) {
        $search     = array('ą', 'ć', 'ę', 'ł', 'ń', 'ó', 'ś', 'ź', 'ż', 'Ą', 'Ć', 'Ę', 'Ł', 'Ń', 'Ó', 'Ś', 'Ź', 'Ż');
        $replace    = array('a', 'c', 'e', 'l', 'n', 'o', 's', 'z', 'z', 'A', 'C', 'E', 'L', 'N', 'O', 'S', 'Z', 'Z');
        return str_replace($search, $replace, $phrase);
    }

    /**
     * @param $x1
     * @param $y1
     * @param $x2
     * @param $y2
     * @param $r
     * @return bool
     */
    protected function isInRadius($x1, $y1, $x2, $y2, $r) {
        return $r <= $this -> calculateDistance($x1, $y1, $x2, $y2);
    }

    /**
     * @param $x1
     * @param $y1
     * @param $x2
     * @param $y2
     * @return float
     */
    protected function calculateDistance($x1, $y1, $x2, $y2) {
        return sqrt( pow( $x1 - $x2, 2 ) + pow( $y1 - $y2, 2 ) ) * 111.1;
    }
}
