<?php

namespace App\Http\Controllers;

use App\Services\CalendarService;
use Illuminate\Http\Request;

class CalendarController extends Controller
{

    protected $calendarSV;

    public function __construct(CalendarService $calendarSV)
    {
       $this->calendarSV = $calendarSV;
    }

    public function attendances(Request $request)
    {
        return $this->calendarSV->attendances($request);
    }

    public function duration(Request $request)
    {
        return $this->calendarSV->duration($request);
    }
}
