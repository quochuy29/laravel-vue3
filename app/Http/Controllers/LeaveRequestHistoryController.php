<?php

namespace App\Http\Controllers;

use App\Services\LeaveRequestHistoryService;
use Illuminate\Http\Request;

class LeaveRequestHistoryController extends Controller
{
    protected $leaveRequestHistorySV;

    public function __construct(LeaveRequestHistoryService $leaveRequestHistorySV)
    {
        $this->leaveRequestHistorySV = $leaveRequestHistorySV;
    }

    public function myHistory()
    {
        return $this->leaveRequestHistorySV->myHistory();
    }
}
