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
}
