<?php

namespace App\Http\Controllers;

use App\Enums\QueueStatus;
use App\Models\QueueEntry;
use App\Services\QueueService;
use App\Services\SchedulingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KitchenController extends Controller
{
    public function index(SchedulingService $scheduler): View
    {
        $current = QueueEntry::with('order.items')->where('status', QueueStatus::Processing->value)->oldest('processing_started_at')->first();
        $waiting = $scheduler->orderedWaiting();
        $ready = QueueEntry::with('order')->where('status', QueueStatus::Ready->value)->oldest('ready_at')->get();

        return view('kitchen.index', compact('current', 'waiting', 'ready', 'scheduler'));
    }

    public function claim(SchedulingService $scheduler): RedirectResponse
    {
        $queue = $scheduler->claimNext(auth()->id());

        return back()->with($queue ? 'success' : 'info', $queue ? "Antrean {$queue->queue_number} mulai diproses." : 'Tidak ada antrean menunggu.');
    }

    public function ready(QueueEntry $queue, QueueService $service): RedirectResponse
    {
        $service->markReady($queue, auth()->id());

        return back()->with('success', "Antrean {$queue->queue_number} siap diambil.");
    }
}
