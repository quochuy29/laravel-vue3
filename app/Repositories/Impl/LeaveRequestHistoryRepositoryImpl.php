<?php

namespace App\Repositories\Impl;

use App\Models\Event;
use App\Models\LeaveRequestHistory;
use App\Repositories\Impl\BaseRepositoryImpl;
use App\Repositories\LeaveRequestHistoryRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class UserRepository
 * @package App\Repositories\Impl
 * @version July 28, 2022, 9:32 am UTC
 * @author TIMESHEET
 */

class LeaveRequestHistoryRepositoryImpl extends BaseRepositoryImpl implements LeaveRequestHistoryRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return LeaveRequestHistory::class;
    }

    public function listEvent($request)
    {
        $datas = Event::pluck('title', 'date')->toArray();

        return $datas;
    }

    public function insertHistoryLeaveRequest() 
    {
        $sql = "INSERT INTO `leave_request_histories` (`user_code`, `user_name`, `transaction_time`,`active`, `amount`, `note`)
                SELECT `user_code`, `user_name`, CAST(`updated_at` AS DateTime), 'Cộng phép năm', 1, ''  
                FROM `leave_requests` WHERE `deleted_at` IS NULL AND CAST(`updated_at` AS DATE) = CURDATE()";
        
        DB::statement($sql);
    }

}