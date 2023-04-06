<?php

namespace App\Services;

use DateTime;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Mail\sendMailRequest;
use Illuminate\Support\Facades\Mail;
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
    protected $calendarSer;

    const TABLE_APPROVE_STATUS = 'approve_status';

    public function __construct(
        UserRepositoryImpl $userRepo,
        RequestTypeRepositoryImpl $requestTypeRepo,
        CalendarRepositoryImpl $calendarRepo,
        CalendarService $calendarSer)
    {
        parent::__construct();
        $this->userRepo = $userRepo;
        $this->requestTypeRepo = $requestTypeRepo;
        $this->calendarRepo = $calendarRepo;
        $this->calendarSer = $calendarSer;
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
        $startTime = ($this->calendarSer->regexDateTime($timeRequest['startTime'])) ? 
            $timeRequest['startTime'] : 
            Carbon::parse($request->date)->format('Y-m-d') . " {$timeRequest['startTime']}";
        $endTime = ($this->calendarSer->regexDateTime($timeRequest['endTime'])) ? 
            $timeRequest['endTime'] : 
            Carbon::parse($request->date)->format('Y-m-d') . " {$timeRequest['startTime']}";
        $approver = explode('_', $request->approver);
        $requestType = $this->requestTypeRepo->find($request->type, ['request_type_code', 'request_type_name']);
        $calendarCode = $this->calendarRepo->findOneByConditions([
            'user_name' => 'huypq1',
            'user_code' => 'dv900n',
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
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration' => $request->duration,
            'reason' => $request->reason,
            'approve_status' => 0,
            'user_create_name' => 'huypq1',
            'user_create_code' => 'dv900n',
            'user_approve_name' => $approver[1],
            'user_approve_code' => $approver[0]
        ];

        $this->_repository->upsert($data, ['request_code'], array_keys($data));
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

        $requestData = $this->_repository->findOneByConditions(['request_code' => $request->code, 'approve_status' => 0]);

        if (!$requestData) {
            return false;
        }

        $this->_repository->upsert(['request_code' => $request->code, 'approve_status' => 1], ['request_code'], ['approve_status']);
        $dataFresh = $requestData->fresh();
        if ($dataFresh['request_type_code'] == 'OT') {
            return;
        }

        switch($dataFresh['request_type_code']) {
            case 'OT':
                return;
                break;
            case 'DO':
            case 'OS':
                $dateToFrom = [];
                $this->buildDateToFrom($dateToFrom, $dataFresh);
                $this->calendarRepo->createCalendarApproveRequest($dateToFrom);
                break;
            case 'ET':
            case 'LT':
            case 'EL':
                $dataBuild = [];
                $this->buildDataUpdateCalendar($dataBuild, $dataFresh);
        
                return $this->calendarRepo->upsert($dataBuild, ['code'], array_keys($dataBuild));
                break;
            default:
                return;
        }
       
    }

    public function rejectRequest(object $request)
    {
        if (!isset($request->code) || $request->code == '') {
            return false;
        }

        $requestData = $this->_repository->findOneByConditions(['request_code' => $request->code, 'approve_status' => 0]);

        if (!$requestData) {
            return false;
        }

        try {
            $this->_repository->upsert(['request_code' => $request->code, 'approve_status' => 0], ['request_code'], ['approve_status']);
            $dataFresh = $requestData->fresh();
            $user = $this->userRepo->findOneByConditions([
                'code' => $dataFresh['user_create_code'],
                'name' => $dataFresh['user_create_name']
            ]);
            $mailInfo = [
                'subject' => '',
                'url' => 'http://localhost:8080/my-request',
                'username' => $dataFresh['user_create_name'] . " ({$dataFresh['user_create_code']})",
                'view' => 'content_send_mail_request',
                'mailTo' => [
                    $user->email
                ]
            ];
    
            Mail::send(new sendMailRequest($mailInfo));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * build data của ngày bắt đầu đến ngày kết thúc cho request OS, DO
     */
    public function buildDateToFrom(&$dateToFrom = [], $dataFresh = [])
    {
        if ($dataFresh['start_time'] == null || $dataFresh['end_time'] == null) {
            return $dateToFrom;
        }

        $start_date = new DateTime($dataFresh['start_time']);
        $end_date = new DateTime($dataFresh['end_time']);
        $interval = new DateInterval('P1D');
        $date_range = new DatePeriod($start_date, $interval, $end_date->modify('-1 day'));
        $duration = 0;
        $column = [
            'code',
            'user_name',
            'user_code',
            'date',
            'checkin_origin',
            'checkout_origin',
            'checkin', 
            'checkout',
            'unpaid_leave',
            'unpaid_flag',
            'late_flag',
            'early_flag'
        ];

        foreach ($date_range as $date) {
            if ($date->format('N') == 6 || $date->format('N') == 7) {
                continue;
            }
            
            $start = clone $date;
            $start->setTime(8, 30);
            $end = clone $date;
            $end->setTime(17, 30);
            $dateToFrom[] = array_combine($column, [
                $dataFresh['calendar_code'] . ($duration == 0 ? '' : $duration), 
                $dataFresh['user_create_name'], 
                $dataFresh['user_create_code'],
                $start->format('Y-m-d'),
                $start->format('Y-m-d H:i:s'),
                $end->format('Y-m-d H:i:s'),
                $start->format('Y-m-d H:i:s'),
                $end->format('Y-m-d H:i:s'),
                '',
                0,
                0,
                0
            ]);
            $duration++;
        }

        $endFull = clone $end_date;
        $end = clone $end_date->modify('+1 day');
        $start =  $end_date->setTime(8, 30);
        $arrFromEnd = [
            $dataFresh['calendar_code'] . ($duration == 0 ? $duration + 1 : $duration), 
            $dataFresh['user_create_name'], 
            $dataFresh['user_create_code'],
            $start->format('Y-m-d'),
            $start->format('Y-m-d H:i:s'), 
            $end->format('Y-m-d H:i:s'),
            $start->format('Y-m-d H:i:s'), 
            $end->format('Y-m-d H:i:s')
        ];

        if (strtotime($end->format('Y-m-d H:i:s')) < strtotime($endFull->modify('+1 day')->setTime(17, 30)->format('Y-m-d H:i:s'))) {
            $time_work = $dataFresh['duration'] - $duration;
            $time_leave = 1 - $time_work;
            $unpaid_flag = 0;
            if ($time_leave > 0.188) {
                $unpaid_flag = 1;
            }
            $dateToFrom[] = array_combine($column, array_merge($arrFromEnd, [$time_leave, $unpaid_flag, 0, ($time_leave > 0) ? 1 : 0]));
        } else {
            $dateToFrom[] = array_combine($column, ['', 0, 0, 0]);
        }

        return $dateToFrom;
    }

    /**
     * buildDataUpdateCalendar build data update calendar with data table request updated
     */
    public function buildDataUpdateCalendar(&$dataBuild, $dataFresh = [])
    {
        $conditionUser = [
            'code' => $dataFresh['calendar_code'],
            'user_code' => $dataFresh['user_create_code'],
            'user_name' => $dataFresh['user_create_name']
        ];
        /**
         * $dataFresh data vừa update khi approve request (table request)
         * $calendarData lấy data trong bảng calendar với code từ data bảng requests mới update (table Calendar)
         */
        $calendarData = $this->calendarRepo->listCalendarUser($conditionUser)->first();

        if (!$calendarData) {
            return false;
        }

        $unpaid = 0;
        $paid = 0;

        /**
         * Tính lại unpaid_leave và paid_leave
         */
        if ($dataFresh['duration'] == $calendarData['unpaid_leave']) {
            $paid = $calendarData['unpaid_leave'];
        } else {
            $unpaid = $calendarData['unpaid_leave'] - $dataFresh['duration'];
            $paid = ($calendarData['paid_leave'] == '') ? $dataFresh['duration'] : $calendarData['paid_leave'] + $dataFresh['duration'];
        }

        /**
         * $dataBuild mảng cơ bản giá trị phải có
         */
        $dataBuild = [
            'code' => $dataFresh['calendar_code'],
            'unpaid_leave' => $unpaid,
            'paid_leave' => $paid
        ];

        /**
         *  update lại flag và checkin, checkout dựa vào cái loại request
         */
        if ($unpaid == 0 && $dataFresh['request_type_code'] == 'ET') {
            $dataBuild['checkin'] = '08:30';
            $dataBuild['checkout'] = '17:30';
            $dataBuild['unpaid_flag'] = 0;
            $dataBuild['late_flag'] = 0;
            $dataBuild['early_flag'] = 0;

            return $dataBuild;
        }

        $dataBuildType = [];

        switch($dataFresh['request_type_code']) {
            case 'ET':
                $dataBuildType = $this->buildDataTypeET($calendarData, $dataFresh);
                break;
            case 'LT':
                $dataBuildType = $this->buildDataTypeLT($calendarData, $dataFresh);
                break;
            case 'EL':
                $dataBuildType = $this->buildDataTypeEL($calendarData, $dataFresh);
                break;
            default:
                break;
        }

        $dataBuild = array_merge($dataBuild, $dataBuildType);

        return $dataBuild;
    }


    public function buildDataTypeET($calendarData = [], $dataFresh = [])
    {
        $defaultStartTime = strtotime('08:30');
        $defaultEndTime = strtotime('17:30');
        $breakStartTime = strtotime('12:00');
        $breakEndTime = strtotime('13:00');
        $latePaidLeave = 0;
        $earlyPaidLeave = 0;
        $lateUnpaidLeave = 0;
        $earlyUnpaidLeave = 0;
        $dataBuild = [];
        if ($calendarData['late_flag'] == 1) {
            $lateTimeAfterUpdate = $this->calendarSer->requestEditEarlyLate(strtotime($calendarData['checkin']), strtotime($dataFresh['start_time']), $breakStartTime, $breakEndTime, $defaultEndTime, $defaultStartTime);
            $lateTimeBeforeUpdate = $this->calendarSer->requestEditEarlyLate(strtotime($calendarData['checkin']), $defaultStartTime, $breakStartTime, $breakEndTime, $defaultEndTime, $defaultStartTime);
            $dataBuild['late_flag'] = (($lateTimeBeforeUpdate - $lateTimeAfterUpdate) <= 0) ? 0 : 1;
            $lateUnpaidLeave = ($dataBuild['late_flag'] == 1)  ?  $calendarData['unpaid_leave'] - $lateTimeAfterUpdate : 0;
            $latePaidLeave = $lateTimeAfterUpdate;
            if ($calendarData['paid_leave'] !== '') {
                $latePaidLeave = $lateTimeAfterUpdate + $calendarData['paid_leave'];
                if ($lateTimeAfterUpdate > $calendarData['unpaid_leave']) {
                    $latePaidLeave = $calendarData['unpaid_leave'] + $calendarData['paid_leave'];
                }
            }
            $dataBuild['checkin'] = $dataFresh['start_time'];
        }

        if ($calendarData['early_flag'] == 1) {
            $earlyTimeAfterUpdate = $this->calendarSer->requestEditEarlyLate(strtotime($dataFresh['end_time']), strtotime($calendarData['checkout']), $breakStartTime, $breakEndTime, $defaultEndTime, $defaultStartTime);
            $earlyTimeBeforeUpdate = $this->calendarSer->requestEditEarlyLate($defaultEndTime, strtotime($dataFresh['end_time']), $breakStartTime, $breakEndTime, $defaultEndTime, $defaultStartTime);
            $dataBuild['early_flag'] = (($earlyTimeBeforeUpdate - $earlyTimeAfterUpdate) <= 0) ? 0 : 1;
            $earlyUnpaidLeave = ($dataBuild['early_flag'] == 1)  ?  $calendarData['unpaid_leave'] - $earlyTimeAfterUpdate : 0;
            $earlyPaidLeave = $earlyTimeAfterUpdate;
            if ($calendarData['paid_leave'] !== '') {
                $earlyPaidLeave = $earlyTimeAfterUpdate + $calendarData['paid_leave'];
                if ($earlyTimeAfterUpdate > $calendarData['unpaid_leave']) {
                    $earlyPaidLeave = $calendarData['unpaid_leave'] + $calendarData['paid_leave'];
                }
            }
            $dataBuild['checkout'] = $dataFresh['end_time'];
        }

        $dataBuild['unpaid_leave'] = $lateUnpaidLeave + $earlyUnpaidLeave;
        $dataBuild['paid_leave'] = $latePaidLeave + $earlyPaidLeave;
        $dataBuild['unpaid_flag'] = ($dataBuild['late_flag'] == 0 && $dataBuild['early_flag'] == 0 && $dataBuild['unpaid_leave'] == 0) ? 0 : 1;

        if ($calendarData['late_flag'] == 0 && $calendarData['early_flag'] == 0 && $calendarData['unpaid_flag'] == 1) {
            $editTimeAfterUpdate = $this->calendarSer->requestEditEarlyLate(strtotime($dataFresh['end_time']), strtotime($dataFresh['start_time']), $breakStartTime, $breakEndTime, $defaultEndTime, $defaultStartTime);
            $dataBuild['unpaid_flag'] = (($calendarData['unpaid_leave'] - $editTimeAfterUpdate) <= 0) ? 0 : 1;
            $dataBuild['unpaid_leave'] = $calendarData['unpaid_leave'] - $editTimeAfterUpdate;
            $dataBuild['paid_leave'] = $editTimeAfterUpdate;
            if ($calendarData['paid_leave'] !== '') {
                $dataBuild['paid_leave'] = $editTimeAfterUpdate + $calendarData['paid_leave'];
                if ($editTimeAfterUpdate > $calendarData['unpaid_leave']) {
                    $dataBuild['paid_leave'] = $calendarData['unpaid_leave'] + $calendarData['paid_leave'];
                }
            }
            $dataBuild['checkin'] = $dataFresh['start_time'];
            $dataBuild['checkout'] = $dataFresh['end_time'];
        }

        return $dataBuild;
    }

    public function buildDataTypeLT($calendarData = [], $dataFresh = [])
    {
        $defaultStartTime = strtotime('08:30');
        $defaultEndTime = strtotime('17:30');
        $breakStartTime = strtotime('12:00');
        $breakEndTime = strtotime('13:00');
        $checkin = strtotime($calendarData['checkin']);
        $timeEnd = strtotime($dataFresh['end_time']);

        $lateTimeAfterUpdate = $this->calendarSer->requestEditEarlyLate($timeEnd, $defaultStartTime, $breakStartTime, $breakEndTime, $defaultEndTime, $defaultStartTime);
        $lateTimeBeforeUpdate = $this->calendarSer->requestEditEarlyLate($checkin, $defaultStartTime, $breakStartTime, $breakEndTime, $defaultEndTime, $defaultStartTime);
        $lateTimeRemain = $lateTimeBeforeUpdate - $lateTimeAfterUpdate;
        $dataBuild['checkin'] = ($lateTimeRemain <= 0) ? '08:30' : $dataFresh['end_time'];
        $dataBuild['late_flag'] = ($lateTimeRemain <= 0) ? 0 : 1;
    }

    public function buildDataTypeEL($calendarData = [], $dataFresh = [])
    {
        $defaultStartTime = strtotime('08:30');
        $defaultEndTime = strtotime('17:30');
        $breakStartTime = strtotime('12:00');
        $breakEndTime = strtotime('13:00');
        $checkout = strtotime($calendarData['checkout']);
        $timeEnd = strtotime($dataFresh['start_time']);

        $lateTimeAfterUpdate = $this->calendarSer->requestEditEarlyLate($defaultEndTime, $timeEnd, $breakStartTime, $breakEndTime, $defaultEndTime, $defaultStartTime);
        $lateTimeBeforeUpdate = $this->calendarSer->requestEditEarlyLate($defaultEndTime, $checkout, $breakStartTime, $breakEndTime, $defaultEndTime, $defaultStartTime);
        $lateTimeRemain = $lateTimeBeforeUpdate - $lateTimeAfterUpdate;
        $dataBuild['checkout'] = ($lateTimeRemain <= 0) ? '17:30' : $dataFresh['start_time'];
        $dataBuild['early_flag'] = ($lateTimeRemain <= 0) ? 0 : 1;
    }
}