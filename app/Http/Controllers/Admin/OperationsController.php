<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SystemHealthService;
use Illuminate\View\View;

class OperationsController extends Controller
{
    public function __invoke(SystemHealthService $healthService): View
    {
        return view('admin.operations', ['health' => $healthService->inspect()]);
    }
}
