<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\RequestRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;

/**
 * Class RequestService
 * @package App\Services
 * @version July 28, 2022, 9:32 am UTC
 * @author TIMESHEET
 */

class RequestService extends BaseService
{

    /**
     * Configure the getRepository
     **/
    public function getRepository()
    {
        return RequestRepository::class;
    }

    public function createRequest(Object $request)
    {
        return 'đậu mé';
    }
}