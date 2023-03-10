<?php

namespace App\Repositories\Impl;

use App\Models\Event;
use App\Repositories\Impl\BaseRepositoryImpl;
use App\Repositories\CalendarRepository;

/**
 * Class UserRepository
 * @package App\Repositories\Impl
 * @version July 28, 2022, 9:32 am UTC
 * @author TIMESHEET
 */

class CalendarRepositoryImpl extends BaseRepositoryImpl implements CalendarRepository
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