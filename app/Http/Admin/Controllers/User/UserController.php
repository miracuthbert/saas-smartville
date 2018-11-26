<?php

namespace Smartville\Http\Admin\Controllers\User;

use Carbon\Carbon;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Users\Events\NewUserInvited;
use Smartville\Domain\Users\Models\User;
use Illuminate\Http\Request;
use Smartville\Domain\Users\Models\UserInvitation;
use Smartville\Http\Admin\Requests\UserInvitationStoreRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('browse', User::class);

        $users = User::filter($request)->paginate();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', User::class);

        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserInvitationStoreRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(UserInvitationStoreRequest $request)
    {
        $this->authorize('create', User::class);

        // catch request
        $data = [];
        $email = $request->email;
        $name = $request->name;

        $role = $request->role;

        $type = 'user_invitation';
        $tokenLifetime = null;

        if (isset($role)) {
            $type = 'admin_user_invitation';
            $tokenLifetime = 360;
            $data = [
                'role_id' => $role,
                'expires_at' => $request->expires_at
            ];
        }

        $invitation = $request->user()->generateInvitationToken($email, $name, $type, $data, $tokenLifetime);

        event(new NewUserInvited($invitation));

        return redirect()->route('admin.users.index')
            ->withSuccess("Invitation sent to {$request->name}.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Smartville\Domain\Users\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
    }
}
