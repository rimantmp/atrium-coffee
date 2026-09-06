<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function __construct(private readonly AuditService $audit) {}

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
