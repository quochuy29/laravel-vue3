<?php

namespace App\Services;

use App\Repositories\CalendarRepository;

/**
 * Class EventService
 * @package App\Services
 * @version July 28, 2022, 9:32 am UTC
 * @author TIMESHEET
 */

class CalendarService extends BaseService
{
    
    /**
     * Configure the getRepository
     **/
    public function getRepository()
    {
        return CalendarRepository::class;
    }

    public function listEvent($request)
    {
        return $this->_repository->listEvent($request);
    }
}