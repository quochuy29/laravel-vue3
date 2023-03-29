<?php

namespace App\Http\Controllers;

use App\Services\RequestService;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    protected $requestSV;

    public function __construct(RequestService $requestSV)
    {
       $this->requestSV = $requestSV;
    }

    public function createRequest(Request $request)
    {
        $this->requestSV->createRequest($request);
    }

    public function getCalendarByDate(Request $request)
    {
        return $this->requestSV->getCalendarByDate($request);
    }

    public function myRequest(Request $request)
    {
        return $this->requestSV->myRequest($request);
    }

    public function approveRequest(Request $request)
    {
        return $this->requestSV->approveRequest($request);
    }
}
