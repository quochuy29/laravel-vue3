<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Request;
use App\Models\User;
use App\Repositories\Impl\RequestRepositoryImpl;
use App\Repositories\Impl\RequestTypeRepositoryImpl;
use App\Repositories\Impl\UserRepositoryImpl;
use App\Repositories\RequestRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use PhpParser\Node\Expr\Cast\Object_;

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
    protected $requestRepo;

    public function __construct(
        UserRepositoryImpl $userRepo,
        RequestTypeRepositoryImpl $requestTypeRepo,
        RequestRepositoryImpl $requestRepo)
    {
        $this->userRepo = $userRepo;
        $this->requestTypeRepo = $requestTypeRepo;
        $this->requestRepo = $requestRepo;
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
        
        $user = $this->userRepo->findOneByConditions([
            'code' => $approver[0],
            'name' => $approver[1]
        ]);
        
        if (!$user) {
            return false;
        }

        $data = [
            'request_code' => 'ghevaysao1',
            'calendar_code' => 'abc',
            'request_type_code' => $requestType->request_type_code,
            'request_type_name' => $requestType->request_type_name,
            'start_time' => $timeRequest['startTime'],
            'end_time' => $timeRequest['endTime'],
            'duration' => $request->duration,
            'reason' => $request->reason,
            'approve_status' => 1,
            'user_create_name' => 'huypq1',
            'user_create_code' => 'huypq1',
            'user_approve_name' => $approver[1],
            'user_approve_code' => $approver[0]
        ];

        $this->requestRepo->upsert($data, array_keys($data), []);
    }
}