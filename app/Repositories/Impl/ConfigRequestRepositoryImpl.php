<?php

namespace App\Repositories\Impl;

use App\Models\ConfigRequest;
use App\Repositories\ConfigRequestRepository;
use App\Repositories\Impl\BaseRepositoryImpl;

/**
 * Class UserRepository
 * @package App\Repositories\Impl
 * @version July 28, 2022, 9:32 am UTC
 * @author Huypq
 */

class ConfigRequestRepositoryImpl extends BaseRepositoryImpl implements ConfigRequestRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return ConfigRequest::class;
    }

    public function getConfigRequest($type = '', $requestTypeCode = '', $requestTypeName = '')
    {
        if ($type == '' || $requestTypeCode == '' || $requestTypeName == '') {
            return false;
        }

        return ConfigRequest::where([
            'type' => $type, 
            'request_type_code' => $requestTypeCode, 
            'request_type_name' => $requestTypeName
            ])->first();
    }
}