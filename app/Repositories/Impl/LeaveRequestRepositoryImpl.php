<?php

namespace App\Repositories\Impl;

use App\Models\Event;
use App\Models\LeaveRequest;
use App\Repositories\Impl\BaseRepositoryImpl;
use App\Repositories\LeaveRequestHistoryRepository;
use App\Repositories\LeaveRequestRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class UserRepository
 * @package App\Repositories\Impl
 * @version July 28, 2022, 9:32 am UTC
 * @author Huypq
 */

class LeaveRequestRepositoryImpl extends BaseRepositoryImpl implements LeaveRequestRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return LeaveRequest::class;
    }

    public function listEvent($request)
    {
        $datas = Event::pluck('title', 'date')->toArray();

        return $datas;
    }

    public function updateQuotas()
    {
        $this->model->where('deleted_at', null)->update(['quotas' => DB::raw('quotas+1'), 'year' => date('Y')]);
    }

}