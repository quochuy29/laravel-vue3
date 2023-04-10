<?php

namespace App\Services;

use App\Repositories\UserRepository;

/**
 * Class EventService
 * @package App\Services
 * @version July 28, 2022, 9:32 am UTC
 * @author Huypq
 */

class UserService extends BaseService
{
    
    /**
     * Configure the getRepository
     **/
    public function getRepository()
    {
        return UserRepository::class;
    }

    public function listEvent($request)
    {
        return $this->_repository->listEvent($request);
    }
}