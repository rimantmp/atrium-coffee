<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OwnerReportExportController extends Controller
{
    public function __invoke(Request $request, ReportService $reports): StreamedResponse
    {
        $filters = $request->validate(['from' => ['required', 'date_format:Y-m-d'], 'to' => ['required', 'date_format:Y-m-d', 'after_or_equal:from']]);

        return response()->streamDownload(function () use ($reports, $filters): void {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, ['Nomor Order', 'Tanggal', 'Pelanggan', 'Tipe', 'Metode', 'Antrean', 'Status', 'Total']);
            foreach ($reports->exportRows($filters['from'], $filters['to']) as $order) {
                fputcsv($stream, [$order->order_number, $order->ordered_at->format('Y-m-d H:i:s'), $order->customer_name, $order->order_type, $order->payments->sortByDesc('id')->first()?->method, $order->queue?->queue_number, $order->status->value, $order->total_amount]);
            }
            fclose($stream);
        }, "laporan-atrium-{$filters['from']}-{$filters['to']}.csv", ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
