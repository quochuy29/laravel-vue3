<?php

namespace App\Repositories\Impl;

use App\Models\RequestType;
use App\Repositories\Impl\BaseRepositoryImpl;
use App\Repositories\RequestTypeRepository;

/**
 * Class RequestTypeRepository
 * @package App\Repositories\Impl
 * @version July 28, 2022, 9:32 am UTC
 * @author TIMESHEET
 */

class RequestTypeRepositoryImpl extends BaseRepositoryImpl implements RequestTypeRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return RequestType::class;
    }

    public function listEvent($request)
    {
    }

}