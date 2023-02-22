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
        $model = new Calendar();
        $model->date = $request->date;
        $model->title = $this->formatDataJson($request->title);
        $model->save();
    }

    public function formatDataJson($data)
    {
        $data1['title'] = $data;
       return json_encode($data1);
    }
}
