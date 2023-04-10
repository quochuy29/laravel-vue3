<?php

namespace App\Repositories\Impl;

use App\Models\Event;
use App\Models\RequestStart;
use App\Repositories\Impl\BaseRepositoryImpl;
use App\Repositories\RequestStartRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class UserRepository
 * @package App\Repositories\Impl
 * @version July 28, 2022, 9:32 am UTC
 * @author Huypq
 */

class RequestStartRepositoryImpl extends BaseRepositoryImpl implements RequestStartRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return RequestStart::class;
    }

    public function listEvent($request)
    {
        $datas = Event::pluck('title', 'date')->toArray();

        return $datas;
    }

    public function updateDateStart()
    {
        $this->model->upsert(['id' => 1, 'date_start_request' => date('Y-m-d')], ['date_start_request'], ['id']);
    }

}