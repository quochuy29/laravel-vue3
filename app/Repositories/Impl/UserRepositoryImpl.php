<?php

namespace App\Repositories\Impl;

use App\Models\User;
use App\Repositories\Impl\BaseRepositoryImpl;
use App\Repositories\UserRepository;

/**
 * Class UserRepository
 * @package App\Repositories\Impl
 * @version July 28, 2022, 9:32 am UTC
 * @author TIMESHEET
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

}