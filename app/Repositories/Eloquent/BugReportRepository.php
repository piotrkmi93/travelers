<?php
/**
 * Created by PhpStorm.
 * User: Piotr Kmiecik
 * Date: 08.12.2016
 * Time: 16:01
 */

namespace App\Repositories\Eloquent;


use App\BugReport;
use App\Repositories\BugReportRepositoryInterface;

class BugReportRepository implements BugReportRepositoryInterface
{
    private $model;

    /**
     * BugReportRepository constructor.
     * @param BugReport $model
     */
    public function __construct(BugReport $model)
    {
        $this -> model = $model;
    }

    /**
     * @param int $user_id
     * @param string $description
     * @return null|BugReport
     */
    public function create($user_id, $description)
    {
        $bugReport = $this -> model -> newInstance();
        $bugReport -> user_id = $user_id;
        $bugReport -> description = $description;
        $bugReport -> is_repaired = false;
        return $bugReport -> save() ? $bugReport : null;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this -> model -> get();
    }


}