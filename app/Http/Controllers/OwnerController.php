<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OwnerController extends Controller
{
    public function __invoke(Request $request, ReportService $reports): View
    {
        $filters = $request->validate(['from' => ['nullable', 'date_format:Y-m-d'], 'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from']]);
        $from = $filters['from'] ?? today(config('app.timezone'))->toDateString();
        $to = $filters['to'] ?? $from;
        ['stats' => $stats, 'topMenus' => $topMenus, 'recent' => $recent] = $reports->period($from, $to);

        return view('owner.index', compact('stats', 'topMenus', 'recent', 'from', 'to'));
    }
}
