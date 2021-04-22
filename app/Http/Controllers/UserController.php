<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

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
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @param User $user
     * @return void
     */
    public function store(UserRequest $request, User $user)
    {
        try {
            $user->create(
                $request->merge([
                    'password' => Hash::make($request->get('password'))
                ])->all()
            );
            flash()->success('User Added!');
            return redirect()->route('user.index');
        } catch(Exception $e) {
            flash()->error("Could not create User ({$e->getMessage()})")->important();
            return back()->withInput();
        }
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
     * @param Request $request
     * @param User $user
     * @return mixed
     */
    // TODO: Fix PasswordRequest
    public function update(Request $request, User $user)
    {
        $user->update(
            $request->merge(['password' => Hash::make($request->get('password'))])
                ->except(
                    [$request->get('password') ? '' : 'password']
                )
        );
        flash()->success('User Updated!');
        return redirect()->back();
    }
}
