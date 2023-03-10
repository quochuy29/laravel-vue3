<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\EventRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;

/**
 * Class EventService
 * @package App\Services
 * @version July 28, 2022, 9:32 am UTC
 * @author TIMESHEET
 */

class EventService extends BaseService
{
    const COLUMN_UPDATE = ['title'];
    const CONDITION_COLUMN = ['date'];

    /**
     * Configure the getRepository
     **/
    public function getRepository()
    {
        return EventRepository::class;
    }

    public function listEvent($request)
    {
        return $this->_repository->listEvent($request);
    }

    public function saveEvent(Object $request)
    {
        $data = $request->title;
        $this->builDataCalendar($data);
        $aryUpdate = $aryInsert = [];
        $getAllDate = Event::pluck('title', 'date')->toArray();
        $this->buildDataUpdateInsert($aryUpdate, $aryInsert, $getAllDate, $data, $request->timeMain);

        if (empty($aryInsert) && empty($aryUpdate)) {
            return false;
        }
        $aryData = array_merge($aryUpdate, $aryInsert);

        $this->_repository->upsert($aryData, self::CONDITION_COLUMN, self::COLUMN_UPDATE);

        return [$aryUpdate, $aryInsert];
    }

    public function builDataCalendar(&$data = [])
    {
        if (empty($data)) {
            return $data;
        }

        $buildData = [];

        foreach ($data as $value) {
            if ($value['date'] == null || $value['date'] == '') {
                continue;
            }

            $value['date'] = Carbon::parse($value['date'])->format('Y-m-d');
            if(array_search($value['date'], array_column($buildData, 'date')) !== false) {
                $buildData[$value['date']]['title'] .= ', ' . json_encode($value);
                $buildData[$value['date']]['date'] = $value['date'];
            } else { 
                $buildData[$value['date']]['date'] = $value['date'];
                $buildData[$value['date']]['title'] = json_encode($value);
            }
        }

        array_walk_recursive($buildData, function (&$v, $k) { 
            if($k == 'title'){ 
                $v = "[$v]"; 
            } 
        });

        $data = $buildData;
    }

    public function buildDataUpdateInsert(&$aryUpdate = [], &$aryInsert = [] ,$dataAllDate = [], &$data = [], $time)
    {
        if (empty($dataAllDate)) {
            return false;
        }

        $dataKey = array_column(array_values($data), 'date');
        
        foreach (array_keys($dataAllDate) as $value) {
            if (in_array($value, $dataKey)) {
                $data[$value]['title'] = json_encode(array_reverse(json_decode($data[$value]['title'], true)));
                if ($value !== $time) {
                    $data[$value]['title'] = json_encode(array_reverse(array_merge($dataAllDate[$value], json_decode($data[$value]['title'], true))));
                   
                }

                $aryUpdate[] = $data[$value];
                unset($data[$value]);
                continue;
            }
        }
        $aryInsert =  array_merge($aryInsert, array_values($data));
    }
}