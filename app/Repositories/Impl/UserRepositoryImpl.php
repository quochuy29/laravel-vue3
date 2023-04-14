<?php

namespace App\Repositories\Impl;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Impl\BaseRepositoryImpl;

/**
 * Class UserRepository
 * @package App\Repositories\Impl
 * @version July 28, 2022, 9:32 am UTC
 * @author Huypq
 */

class UserRepositoryImpl extends BaseRepositoryImpl implements UserRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }

    public function listEvent($request)
    {
    }

    public function getInfor()
    {
        $userLogin = auth()->user();
        $dateNow = Carbon::now()->format('Y-m');
        $query = User::select('users.name as name', 'users.email as email', 'leave_requests.quotas as quotas',
                DB::raw("CAST(`leave_requests`.`created_at` AS Date) as since"),
                DB::raw("SUM(CASE WHEN `requests`.`request_type_code` = 'OT' THEN `requests`.`duration` ELSE 0 END) AS `duration`"),
                DB::raw("SUM(CASE WHEN `requests`.`approve_status` = 1 THEN `requests`.`duration` ELSE 0 END) AS `requested_leave`"),
                DB::raw("SUM(CASE WHEN `requests`.`approve_status` = 0 THEN `requests`.`duration` ELSE 0 END) AS `unrequested_leave`"))
                ->join('leave_requests', 'users.code', '=', 'leave_requests.user_code')
                ->leftJoin('requests', function ($join) use ($dateNow) {
                    $join->on('users.code', '=', 'requests.user_create_code')
                    ->where(DB::raw("DATE_FORMAT(requests.start_time, '%Y-%m')"), '=', $dateNow);
                })
                ->where([
                    'users.code' => $userLogin->code,
                    'leave_requests.deleted_at' => null
                ])->groupBy('users.code')->first();
        return $query;
    }

}