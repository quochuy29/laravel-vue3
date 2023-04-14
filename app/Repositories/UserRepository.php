<?php

namespace App\Repositories;

/**
 * Class UserRepository
 * @package App\Repositories
 * @version July 28, 2022, 9:32 am UTC
 * @author Huypq
*/

interface UserRepository
{
    /**
     * Configure the Model
     **/
    public function model();

    public function upsert(array $value = [], array $condition, array $column);

    public function findOneByConditions($conditions);

    public function getInfor();

}