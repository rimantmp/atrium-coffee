<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->route(match (auth()->user()->role) {
            'KITCHEN' => 'kitchen.index', 'ADMIN' => 'admin.index', 'OWNER' => 'owner.index', default => 'cashier.index',
        });
    }
}
