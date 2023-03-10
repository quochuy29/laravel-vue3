<?php

namespace App\Services;

use App\Repositories\RequestStatusRepository;

/**
 * Class EventService
 * @package App\Services
 * @version July 28, 2022, 9:32 am UTC
 * @author TIMESHEET
 */

class RequestStatusService extends BaseService
{
    
    /**
     * Configure the getRepository
     **/
    public function getRepository()
    {
        return RequestStatusRepository::class;
    }

    public function listEvent($request)
    {
        return $this->_repository->listEvent($request);
    }
}