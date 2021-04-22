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
        if(request()->has('q')) {
            $q = request()->get('q');
            $filter = sprintf('%%%s%%', $q);
            $users = $user->where('email', 'like', $filter)
                ->orWhere('name', 'like', $filter)
                ->paginate(15);
        } else {
            $q = '';
            $users = $user->paginate(15);
        }

        return view('users.index', compact('users', 'q'));
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
