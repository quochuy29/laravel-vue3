<?php

namespace App\Services;

use App\Repositories\LeaveRequestRepository;

/**
 * Class EventService
 * @package App\Services
 * @version July 28, 2022, 9:32 am UTC
 * @author TIMESHEET
 */

class LeaveRequestService extends BaseService
{
    
    /**
     * Configure the getRepository
     **/
    public function getRepository()
    {
        return LeaveRequestRepository::class;
    }

    public function listEvent($request)
    {
        return $this->_repository->listEvent($request);
    }
}