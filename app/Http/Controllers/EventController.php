<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $eventSV;

    public function __construct(EventService $eventSV)
    {
        $this->eventSV = $eventSV;
    }

    public function calendar(Request $request)
    {
        return $this->eventSV->listEvent($request);
    }

    public function saveEvent(EventRequest $request) 
    {
        list($aryUpdate, $aryInsert) = $this->eventSV->saveEvent($request);
        
        return json_encode(['update' => $aryUpdate, 'insert' => $aryInsert, 'status' => 200]);
    }

}
