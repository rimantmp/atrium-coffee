<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function __invoke(Request $request): View
    {
        $filters = $request->validate(['action' => ['nullable', 'string', 'max:80'], 'entity_type' => ['nullable', 'string', 'max:100'], 'from' => ['nullable', 'date_format:Y-m-d'], 'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from']]);
        $logs = DB::table('audit_logs')->leftJoin('users', 'users.id', '=', 'audit_logs.user_id')->select('audit_logs.*', 'users.name as user_name')->when($filters['action'] ?? null, fn ($query, $action) => $query->where('audit_logs.action', $action))->when($filters['entity_type'] ?? null, fn ($query, $type) => $query->where('audit_logs.entity_type', $type))->when($filters['from'] ?? null, fn ($query, $from) => $query->whereDate('audit_logs.created_at', '>=', $from))->when($filters['to'] ?? null, fn ($query, $to) => $query->whereDate('audit_logs.created_at', '<=', $to))->orderByDesc('audit_logs.created_at')->orderByDesc('audit_logs.id')->paginate(25)->withQueryString();
        $actions = DB::table('audit_logs')->distinct()->orderBy('action')->pluck('action');
        $entityTypes = DB::table('audit_logs')->distinct()->orderBy('entity_type')->pluck('entity_type');

        return view('admin.audit-logs', compact('logs', 'actions', 'entityTypes', 'filters'));
    }
}
