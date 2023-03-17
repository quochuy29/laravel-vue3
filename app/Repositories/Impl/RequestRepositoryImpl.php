<?php

namespace App\Repositories\Impl;

use App\Models\Event;
use App\Repositories\Impl\BaseRepositoryImpl;
use App\Repositories\RequestRepository;

/**
 * Class UserRepository
 * @package App\Repositories\Impl
 * @version July 28, 2022, 9:32 am UTC
 * @author TIMESHEET
 */

class RequestRepositoryImpl extends BaseRepositoryImpl implements RequestRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Event::class;
    }
}