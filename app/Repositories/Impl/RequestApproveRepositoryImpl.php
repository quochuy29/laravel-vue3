<?php

namespace App\Repositories\Impl;

use App\Models\Event;
use App\Repositories\Impl\BaseRepositoryImpl;
use App\Repositories\RequestApproveRepository;

/**
 * Class UserRepository
 * @package App\Repositories\Impl
 * @version July 28, 2022, 9:32 am UTC
 * @author TIMESHEET
 */

class RequestApproveRepositoryImpl extends BaseRepositoryImpl implements RequestApproveRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Event::class;
    }

    public function listEvent($request)
    {
        $datas = Event::pluck('title', 'date')->toArray();

        return $datas;
    }

}