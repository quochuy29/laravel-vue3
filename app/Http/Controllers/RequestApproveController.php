<?php

namespace App\Http\Controllers;

use App\Services\RequestApproveService;
use Illuminate\Http\Request;

class RequestApproveController extends Controller
{
    protected $requestApprove;

    public function __construct(RequestApproveService $requestApprove)
    {
        $this->requestApprove = $requestApprove;
    }

    public function listApprovers(Request $request)
    {
        return $this->requestApprove->listApprovers($request);
    }
}
