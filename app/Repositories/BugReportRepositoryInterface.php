<?php
/**
 * Created by PhpStorm.
 * User: Piotr Kmiecik
 * Date: 08.12.2016
 * Time: 15:59
 */

namespace App\Repositories;


interface BugReportRepositoryInterface
{
    public function create($user_id, $description);
    public function all();
}