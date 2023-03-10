<?php

namespace App\Repositories\Impl;

use App\Models\Event;
use App\Repositories\Impl\BaseRepositoryImpl;
use App\Repositories\EventRepository;
use Illuminate\Support\Facades\Http;

/**
 * Class UserRepository
 * @package App\Repositories\Impl
 * @version July 28, 2022, 9:32 am UTC
 * @author TIMESHEET
 */

class EventRepositoryImpl extends BaseRepositoryImpl implements EventRepository
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