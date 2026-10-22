<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private readonly AuditService $audit) {}

    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $role = $request->string('role')->toString();
        $status = $request->string('status')->toString();
        $users = User::query()
            ->when($search, fn ($query, $search) => $query->where(fn ($query) => $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")))
            ->when(in_array($role, ['ADMIN', 'CASHIER', 'KITCHEN', 'OWNER'], true), fn ($query) => $query->where('role', $role))
            ->when($status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $user = User::create($data);
        $this->audit->record('USER_CREATED', $user, [], $user->only(['name', 'email', 'role', 'is_active']), null, $request->user()->id);

        return back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user) && (! $request->boolean('is_active') || $request->string('role')->toString() !== 'ADMIN')) {
            return back()->withErrors(['user' => 'Anda tidak dapat menonaktifkan atau mengubah role akun sendiri.']);
        }
        $old = $user->only(['name', 'email', 'role', 'is_active']);
        $data = $request->safe()->except('password');
        $data['is_active'] = $request->boolean('is_active');
        if ($request->filled('password')) {
            $data['password'] = $request->string('password')->toString();
        }
        $user->update($data);
        $this->audit->record('USER_UPDATED', $user, $old, $user->only(['name', 'email', 'role', 'is_active']), null, $request->user()->id);

        return back()->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function status(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user) && $user->is_active) {
            return back()->withErrors(['user' => 'Anda tidak dapat menonaktifkan akun sendiri.']);
        }

        $old = $user->only(['is_active']);
        $user->update(['is_active' => ! $user->is_active]);
        $this->audit->record('USER_STATUS_UPDATED', $user, $old, $user->only(['is_active']), null, $request->user()->id);

        return back()->with('success', 'Status pengguna berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (request()->user()->is($user)) {
            return back()->withErrors(['user' => 'Anda tidak dapat menghapus akun sendiri.']);
        }
        $old = $user->only(['name', 'email', 'role', 'is_active']);
        $user->delete();
        $this->audit->record('USER_DELETED', $user, $old, [], null, request()->user()->id);

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
