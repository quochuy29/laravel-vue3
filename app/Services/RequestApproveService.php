<?php

namespace App\Services;

use App\Repositories\RequestApproveRepository;

/**
 * Class EventService
 * @package App\Services
 * @version July 28, 2022, 9:32 am UTC
 * @author TIMESHEET
 */

class RequestApproveService extends BaseService
{
    
    /**
     * Configure the getRepository
     **/
    public function getRepository()
    {
        return RequestApproveRepository::class;
    }

    public function listApprovers($request)
    {
        return $this->_repository->listApprovers($request);
    }
}