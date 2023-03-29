<?php

namespace App\Repositories;

/**
 * Class UserRepository
 * @package App\Repositories
 * @version July 28, 2022, 9:32 am UTC
 * @author TIMESHEET
*/

interface RequestRepository
{
    /**
     * Configure the Model
     **/
    public function model();

    public function upsert(array $value = [], array $condition, array $column);

    public function getRequestByConditions(array $condition = [], $tableJoin, $request);

}