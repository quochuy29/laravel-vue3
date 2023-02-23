<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
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
        $data = Calendar::get();

        return json_encode($data);
    }

    public function add(Request $request) {
        $calendar = Calendar::where('date', '=', $request->date)->first();

        if ($calendar) {
            $calendar->title = $this->buildDataTitleCalendar($request->title ?? [], $calendar->title);
            $calendar->save();
            return json_encode(['status' => 200]);
        }

        $model = new Calendar();
        $model->date = $request->date;
        $model->title = $this->buildDataTitleCalendar($request->title ?? []);
        $model->save();

        return json_encode(['status' => 200]);
    }

    public function buildDataTitleCalendar($data = [], $dataDate = '[]')
    {
        if ($data == '') {
            return $data;
        }

        $buildData = [];
        foreach ($data as $key => $value) {
            if ($value['type'] == '') {
                $value['type'] = '';
                $data[$key] = $value;
            }

            if ($value['content'] == '') {
                $value['content'] = '';
                $data[$key] = $value;
            }
        }
        $dataNew = json_decode($dataDate, true);

        if (!empty($dataNew)) {
            $data = array_merge($data, $dataNew);
        }

        $buildData = json_encode($data);
        return $buildData;
    }
}
