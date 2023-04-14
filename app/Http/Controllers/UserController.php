<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $useService;

    public function __construct(UserService $useService)
    {
        $this->useService = $useService;
    }

    public function infor()
    {
        return $this->useService->getInfor();
    }
}
