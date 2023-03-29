<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Repositories\RequestRepository;
use App\Repositories\Impl\UserRepositoryImpl;
use App\Repositories\Impl\CalendarRepositoryImpl;
use App\Repositories\Impl\RequestTypeRepositoryImpl;

/**
 * Class RequestService
 * @package App\Services
 * @version July 28, 2022, 9:32 am UTC
 * @author TIMESHEET
 */

class RequestService extends BaseService
{
    protected $userRepo;
    protected $requestTypeRepo;
    protected $calendarRepo;
    const TABLE_APPROVE_STATUS = 'approve_status';

    public function __construct(
        UserRepositoryImpl $userRepo,
        RequestTypeRepositoryImpl $requestTypeRepo,
        CalendarRepositoryImpl $calendarRepo)
    {
        parent::__construct();
        $this->userRepo = $userRepo;
        $this->requestTypeRepo = $requestTypeRepo;
        $this->calendarRepo = $calendarRepo;
    }

    /**
     * Configure the getRepository
     **/
    public function getRepository()
    {
        return RequestRepository::class;
    }

    public function getCalendarByDate(Object $request)
    {
        return $this->_repository->findByColumn('date', $request->date);
    }

    public function createRequest(Object $request)
    {
        if (empty($request->all())) {
            return false;
        }

        $timeRequest = $request->time_request;
        $approver = explode('_', $request->approver);
        $requestType = $this->requestTypeRepo->find($request->type, ['request_type_code', 'request_type_name']);
        $calendarCode = $this->calendarRepo->findOneByConditions([
            'user_name' => 'huypq',
            'user_code' => 'pLbMuC',
            'date' => Carbon::parse($request->date)->format('Y-m-d')
        ]);

        $user = $this->userRepo->findOneByConditions([
            'code' => $approver[0],
            'name' => $approver[1]
        ]);

        if (!$user && !$calendarCode) {
            return false;
        }

        $data = [
            'request_code' => Str::random(6),
            'calendar_code' => $calendarCode->code ?? Str::random(6),
            'request_type_code' => $requestType->request_type_code,
            'request_type_name' => $requestType->request_type_name,
            'date_request' => Carbon::parse($request->date)->format('Y-m-d'),
            'start_time' => Carbon::parse($timeRequest['startTime'])->format('H:i:s'),
            'end_time' => Carbon::parse($timeRequest['endTime'])->format('H:i:s'),
            'duration' => $request->duration,
            'reason' => $request->reason,
            'approve_status' => 1,
            'user_create_name' => 'huypq1',
            'user_create_code' => 'dv900n',
            'user_approve_name' => $approver[1],
            'user_approve_code' => $approver[0]
        ];

        $this->_repository->upsert($data, array_keys($data), []);
    }

    public function myRequest(Object $request)
    {
        return $this->_repository->getRequestByConditions([
            'user_create_name' => 'huypq1',
            'user_create_code' => 'dv900n',
        ], self::TABLE_APPROVE_STATUS, $request);
    }

    public function approveRequest(Object $request)
    {
        if (!isset($request->code) || $request->code == '') {
            return false;
        }

        $requestCode = $this->_repository->findOneByConditions(['request_code' => $request->code]);
        if ($requestCode) {
            $this->_repository->upsert(['request_code' => $request->code, 'approve_status' => 1], ['request_code'], ['approve_status']);
        }
    }
}