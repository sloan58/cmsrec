<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use App\Http\Requests\PasswordRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param User $user
     * @return View
     */
    public function index(User $user)
    {
        return view('users.index', ['users' => $user->paginate(15)]);
    }

    /**
     * Display a listing of the users
     *
     * @param User $user
     * @return View
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Display a listing of the users
     *
     * @param User $user
     * @return View
     */
    public function destroy(User $user)
    {
        $user->delete();
        flash()->success('User Deleted!');
        return redirect()->route('user.index');
    }

    /**
     * @param PasswordRequest $request
     * @param User $user
     * @return mixed
     */
    public function update(PasswordRequest $request, User $user)
    {
        $user->update(['password' => \Hash::make($request->get('password'))]);
        flash()->success('Password Updated!');
        return back();
    }
}
