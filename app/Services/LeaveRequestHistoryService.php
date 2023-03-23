<?php

namespace App\Services;

use App\Repositories\LeaveRequestHistoryRepository;
use App\Repositories\Impl\LeaveRequestRepositoryImpl;

/**
 * Class EventService
 * @package App\Services
 * @version July 28, 2022, 9:32 am UTC
 * @author TIMESHEET
 */

class LeaveRequestHistoryService extends BaseService
{
    protected $leaveRequestRepo;

    public function __construct(LeaveRequestRepositoryImpl $leaveRequestRepo)
    {
        $this->leaveRequestRepo = $leaveRequestRepo;
    }
    
    /**
     * Configure the getRepository
     **/
    public function getRepository()
    {
        return LeaveRequestHistoryRepository::class;
    }

    public function listEvent($request)
    {
        return $this->_repository->listEvent($request);
    }

    public function updateDateStart()
    {
        $leaveRequest = $this->leaveRequestRepo->model();
        $data = $leaveRequest::get();

        $this->_repository->whereIn();
    }
}