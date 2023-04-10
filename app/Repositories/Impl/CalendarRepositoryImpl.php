<?php

namespace App\Repositories\Impl;

use Carbon\Carbon;
use App\Models\Calendar;
use App\Repositories\CalendarRepository;
use App\Repositories\Impl\BaseRepositoryImpl;

/**
 * Class UserRepository
 * @package App\Repositories\Impl
 * @version July 28, 2022, 9:32 am UTC
 * @author Huypq
 */

class CalendarRepositoryImpl extends BaseRepositoryImpl implements CalendarRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Calendar::class;
    }

    public function listCalendarUser($conditions)
    {
        return $this->model->where($conditions)->get();
    }

    public function getCalendarByCode($codeRequest)
    {
        return $this->model->where('code', $codeRequest)->first();
    }

    public function findOneByConditions($conditions)
    {
        return $this->model->where($conditions)->first();
    }

    public function createCalendarApproveRequest(array $toFromDate = [])
    {
        return $this->model->insert($toFromDate);
    }

}