<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class OptionsController extends Controller
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this -> userRepository = $userRepository;
    }

    public function index()
    {
        return view('options');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $current_password = $request -> current_password;
        $password = $request -> password;
        $confirm_password = $request -> confirm_password;

        if( strlen($current_password) < 6 || strlen($password) < 6 || strlen($confirm_password) < 6 )
        {
            return view('options', ['success' => false, 'info' => 'Hasło jest za krótkie (conajmniej 6 znaków)']);
        }

        if( !$this -> userRepository -> passwordCorrect($user -> id, $current_password) )
        {
            return view('options', ['success' => false, 'info' => 'Hasło niepoprawne']);
        }

        if ( $password !== $confirm_password )
        {
            return view('options', ['success' => false, 'info' => 'Hasła do siebie nie pasują']);
        }

        if ( $password === $current_password )
        {
            return view('options', ['success' => false, 'info' => 'Nowe hasło nie może być takie samo jak stare']);
        }

        $this -> userRepository -> updatePassword( $user -> id, bcrypt($password) );

        return view('options', ['success' => true, 'info' => 'Hasło zostało zaktualizowane']);
    }
	
	public function changeFirstAndLastName(Request $request)
	{
		$this -> userRepository -> updateFirstAndLastName(Auth::user()->id, $request->first_name, $request->last_name);
		return view('options', ['fln_success' => true, 'fln_info' => 'Dane zostały zaktualizowane']);
	}
}
