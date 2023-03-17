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

    public function attendances($request)
    {
        return $this->_repository->findOneByColumn('date', $request->date);
    }

    public function duration($request)
    {
        $duration = 0;
        if ($request->start_time == '' || $request->end_time == '') {
            return $duration;
        }

        $startTime = ($this->regexDateTime($request->start_time) == true) ? $request->start_time : strtotime($request->start_time);
        $endTime = ($this->regexDateTime($request->end_time) == true) ? $request->end_time : strtotime($request->end_time);
        $defaultStartTime = strtotime('08:30');
        $defaultEndTime = strtotime('17:30');
        $breakStartTime = strtotime('12:00');
        $breakEndTime = strtotime('13:00');

        switch($request->type) {
            case 1:
            case 2:
            case 3:
                return $this->requestEditEarlyLate($endTime, $startTime, $breakStartTime, $breakEndTime, $defaultEndTime, $defaultStartTime);
                break;
            case 4:
                return $this->requestOT($endTime, $startTime);
                break;
            case 5:
            case 6:
                return $this->requestOnsite($endTime, $startTime, $breakStartTime, $breakEndTime, $defaultEndTime, $defaultStartTime);
                break;
        }
    }

    public function requestEditEarlyLate($endTime, $startTime, $breakStartTime, $breakEndTime, $defaultEndTime, $defaultStartTime)
    {
        if ($endTime >= $defaultEndTime) {
            $endTime = $defaultEndTime;
        }

        if ($startTime <= $defaultStartTime) {
            $startTime = $defaultStartTime;
        }

        if ($endTime <= $breakEndTime && $endTime >= $breakStartTime) {
            $endTime = $breakStartTime;
        }

        if ($startTime <= $breakEndTime && $startTime >= $breakStartTime) {
            $startTime = $breakEndTime;
        }

        if ($startTime <= $breakStartTime && $endTime >= $breakEndTime) {
            $duration = (($endTime - $startTime - 3600)/8)/3600;
        } else {
            $duration = (($endTime - $startTime)/8)/3600;
        }

        if ($endTime <= $startTime) {
            $duration = 0;
        }

        if ($duration > 1) {
            $duration = 1;
        }
        
        return round($duration, 3);
    }

    public function requestOT($endTime, $startTime)
    {
        $duration = ($endTime - $startTime)/3600;

        if ($duration > 4) {
            $duration = $duration - 1;
        }

        if ($duration > 8) {
            $duration = $duration - 1.5;
        }

        if ($duration > 12) {
            $duration = $duration - 2;
        }

        return round($duration, 3);
    }

    public function requestOnsite($endTime, $startTime, $breakStartTime, $breakEndTime, $defaultEndTime, $defaultStartTime)
    {
        $dateFr = date('Y-m-d', strtotime($startTime));
        $dateT = date('Y-m-d', strtotime($endTime));
        $hourFr = strtotime(date('H:i', strtotime($startTime)));
        $hourT = strtotime(date('H:i', strtotime($endTime)));
        $duration = 0;
        if ($hourT >= $defaultEndTime) {
            $hourT = $defaultEndTime;
        }

        if ($hourFr <= $defaultStartTime) {
            $hourFr = $defaultStartTime;
        }

        if ($hourT <= $breakEndTime && $hourT >= $breakStartTime) {
            $hourT = $breakStartTime;
        }

        if ($hourFr <= $breakEndTime && $hourFr >= $breakStartTime) {
            $hourFr = $breakEndTime;
        }

        if ($dateT > $dateFr) {
            $duration = 1;
        } else {
            $duration = 0;
        }

        if ($hourFr <= $breakStartTime && $hourT >= $breakEndTime) {
            $durations = (($hourT - $hourFr - 3600)/8)/3600;
            if ($dateT < $dateFr) {
                $durations = 0;
            }
        } else {
            $durations = (($hourT - $hourFr)/8)/3600;
        }

        if ($durations > 1) {
            $durations = 1;
        }

        return round($duration + $durations, 3);
    }

    public function regexDateTime($dateTime = '')
    {
        $regex = '/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29)) (([0-1]{1}[0-9]{1})|([2]{1}[0-3]{1}))([:])([0-5]{1}[0-9]{1})$/';

        if (preg_match($regex, $dateTime)) {
            return true;
        }

        return false;
    }
}