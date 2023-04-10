<?php

namespace App\Repositories;

/**
 * Class UserRepository
 * @package App\Repositories
 * @version July 28, 2022, 9:32 am UTC
 * @author Huypq
*/

interface CalendarRepository
{
    /**
     * Configure the Model
     **/
    public function model();

    public function listCalendarUser($conditions);

    public function upsert(array $value = [], array $condition, array $column);

    public function getCalendarByCode($codeRequest);

    public function createCalendarApproveRequest(array $toFromDate = []);
}