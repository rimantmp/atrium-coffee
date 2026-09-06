<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRefundRequest;
use App\Models\Payment;
use App\Services\RefundService;
use Illuminate\Http\RedirectResponse;

class CashierRefundController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreRefundRequest $request, Payment $payment, RefundService $service): RedirectResponse
    {
        $service->refund($payment, $request->user()->id, $request->string('reason')->toString());

        return back()->with('success', 'Refund penuh berhasil diproses dan antrean dibatalkan.');
    }
}
