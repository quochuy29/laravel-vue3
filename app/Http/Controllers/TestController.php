<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestController extends Controller
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
        $datas = Calendar::pluck('title', 'date');

        return json_encode($datas);
    }

    public function add(Request $request) 
    {
        $data = $request->title;
        $this->builDataCalendar($data);
        $aryUpdate = [];
        $getAllDate = Calendar::pluck('title', 'date')->toArray();
        $aryInsert = [];
        $this->buildDataUpdateInsert($aryUpdate, $aryInsert, $getAllDate, $data);
        $this->insertDataCalendar($aryUpdate, $aryInsert);
        
        return json_encode(['status' => 200]);

    }

    public function buildDataUpdateInsert(&$update = [], &$insert = [], $dataAllDate = [], $data = [])
    {
        if (empty($dataAllDate)) {
            return false;
        }

        $dataKey = array_column(array_values($data), 'date');

        foreach (array_keys($dataAllDate) as $value) {
            if (in_array($value, $dataKey)) {
                $data[$value]['title'] = json_encode(array_merge($dataAllDate[$value], json_decode($data[$value]['title'], true)));
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

            $date = Carbon::parse($value['date'])->format('Y-m-d');
            unset($value['date']);
            if(($index = array_search($date, array_column($buildData, 'date'))) !== false) {
                $buildData[$date]['title'] .= ', ' . json_encode($value);
                $buildData[$date]['date'] = $date;
            } else { 
                $buildData[$date]['date'] = $date;
                $buildData[$date]['title'] = json_encode($value);
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

        $model = Calendar::insert($aryInsert);

        foreach ($aryUpdate as $value) {
            Calendar::where('date', '=', $value['date'])->update($value);
        }
    }
}
