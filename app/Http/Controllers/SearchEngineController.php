<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

use App\Http\Requests;

class SearchEngineController extends Controller
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this -> userRepository = $userRepository;
    }

    public function search($phrase)
    {
        $users = $this -> userRepository -> searchByPhrase($phrase);
        dd($users);
    }
}
