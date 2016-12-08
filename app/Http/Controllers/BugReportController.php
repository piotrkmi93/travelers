<?php

namespace App\Http\Controllers;

use App\Repositories\BugReportRepositoryInterface;
use App\Repositories\PhotoRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

class BugReportController extends Controller
{
    private $bugReportRepository,
            $userRepository,
            $photoRepository;

    public function __construct(BugReportRepositoryInterface $bugReportRepository,
                                UserRepositoryInterface $userRepository,
                                PhotoRepositoryInterface $photoRepository)
    {
        $this -> bugReportRepository = $bugReportRepository;
        $this -> userRepository = $userRepository;
        $this -> photoRepository = $photoRepository;
    }

    public function index()
    {
        $bugReports = $this -> bugReportRepository -> all() -> toArray();

        foreach ($bugReports as &$bugReport){
            $user = $this -> userRepository -> getById($bugReport['user_id']);
            $bugReport['user_name'] = $user -> first_name . ' ' . $user -> last_name;
            $bugReport['avatar'] = asset($this -> photoRepository -> getById($user -> avatar_photo_id) -> thumb_url);
            $bugReport['created_at'] = (new Carbon($bugReport['created_at']))->toDateString() . ' o ' . (new Carbon($bugReport['created_at']))->toTimeString();
            $bugReport['updated_at'] = (new Carbon($bugReport['updated_at']))->toDateString() . ' o ' . (new Carbon($bugReport['updated_at']))->toTimeString();
            $bugReport['user_link'] = asset('user/' . $user -> username . '#/board');
        }

//        dd($bugReports);

        return view('bug_reports', compact('bugReports'));
    }

    public function add(Request $request)
    {
        $user_id = $request -> user_id;
        $description = $request -> description;

        $bugReport = $this -> bugReportRepository -> create($user_id, $description);

        return response() -> json(['success' => isset($bugReport)]);
    }
}
