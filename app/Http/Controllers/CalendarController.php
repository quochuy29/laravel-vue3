<?php

namespace App\Http\Controllers;

use App\Services\CalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{

    protected $calendarSV;

    public function __construct(CalendarService $calendarSV)
    {
       $this->calendarSV = $calendarSV;
    }

    public function listCalendarUser()
    {
        return $this->calendarSV->listCalendarUser();
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
