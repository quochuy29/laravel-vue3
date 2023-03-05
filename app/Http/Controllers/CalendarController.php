<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarRequest;
use App\Models\Calendar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function abc()
    {
        return 1;
    }

    public function upload()
    {
        $a = [
            "name"=> "xxx.png",
            "status"=> "done",
            "url"=> "https://zos.alipayobjects.com/rmsportal/jkjgkEfvpUPVyRjUImniVslZfWPnJuuZ.png",
            "thumbUrl"=>"https://zos.alipayobjects.com/rmsportal/jkjgkEfvpUPVyRjUImniVslZfWPnJuuZ.png"
        ];

        return json_encode($a);
        
    }

    public function calendar()
    {
        $datas = Calendar::pluck('title', 'date')->toArray();

        return $datas;
    }

    public function add(CalendarRequest $request) 
    {
        $data = $request->title;
        $this->builDataCalendar($data);
        $aryUpdate = [];
        $getAllDate = Calendar::pluck('title', 'date')->toArray();
        $aryInsert = [];
        $this->buildDataUpdateInsert($aryUpdate, $aryInsert, $getAllDate, $data, $request->action);
        $this->insertDataCalendar($aryUpdate, $aryInsert);
        
        return json_encode(['status' => 200]);

    }

    public function buildDataUpdateInsert(&$update = [], &$insert = [], $dataAllDate = [], $data = [], $action = 'add')
    {
        if (empty($dataAllDate)) {
            return false;
        }

        $dataKey = array_column(array_values($data), 'date');

        foreach (array_keys($dataAllDate) as $value) {
            if (in_array($value, $dataKey)) {
                $title = array_reverse(array_merge($dataAllDate[$value], json_decode($data[$value]['title'], true)));
                if ($action == 'update') {
                    $title = array_reverse(json_decode($data[$value]['title'], true));
                }
                $data[$value]['title'] = json_encode($title);
                $update[] = $data[$value];
                unset($data[$value]);
                continue;
            }
        }

        $insert = array_merge($insert, array_values($data));
    }

    public function builDataCalendar(&$data = [])
    {
        if (empty($data)) {
            return $data;
        }

        $buildData = [];

        foreach ($data as $key => $value) {
            if ($value['date'] == null || $value['date'] == '') {
                continue;
            }

            $value['date'] = Carbon::parse($value['date'])->format('Y-m-d');
            if(($index = array_search($value['date'], array_column($buildData, 'date'))) !== false) {
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

    public function insertDataCalendar($aryUpdate = [], $aryInsert = [])
    {
        if (empty($aryInsert) && empty($aryUpdate)) {
            return false;
        }
        $aryData = array_merge($aryUpdate, $aryInsert);

        Calendar::upsert($aryData, ['date'], ['title']);
    }
}
