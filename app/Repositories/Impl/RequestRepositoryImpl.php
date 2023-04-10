<?php

namespace App\Repositories\Impl;

use App\Models\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\RequestRepository;
use App\Repositories\Impl\BaseRepositoryImpl;

/**
 * Class UserRepository
 * @package App\Repositories\Impl
 * @version July 28, 2022, 9:32 am UTC
 * @author Huypq
 */

class RequestRepositoryImpl extends BaseRepositoryImpl implements RequestRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Request::class;
    }

    public function findByColumn(string $column , string $value)
    {
        return $this->model->where($column, $value)->first();
    }

    public function getRequestByConditions(array $conditions = [], string $tableJoin, object $request)
    {
        $query =  $this->model::leftJoin("$tableJoin as originJoin", 'requests.approve_status', '=', "originJoin.id")
        ->select('originJoin.approve_status_name as status', 
                DB::raw("CAST(`requests`.`created_at` AS DateTime) as request_at"),
                'requests.*')
        ->where($conditions)
        ->groupBy('requests.request_code');
        
        if (trim($request->field) !== '' && trim($request->order) !== '') {
            $query = $query->orderBy($request->field, $request->order);
        }
        if (count($request->search) > 0) {
            $query = $query->havingRaw('MONTH(request_at) = ?', [$request->search['month']]);
            $query = $query->havingRaw('YEAR(request_at) = ?', [$request->search['year']]);
        }

        return $query->paginate(10);
    }
}