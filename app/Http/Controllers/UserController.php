<?php

namespace App\Http\Controllers;

use App\Models\ModelHasRole;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user->hasRole('super_admin')) {
            $users = User::orderBy('id', 'DESC')->paginate(10);
        } else if ($user->hasRole('admin')) {
            $users = User::where('created_by_id', $user->id)
                ->orderBy('id', 'DESC')
                ->paginate(10);
        } else {
            abort(403, 'Unauthorized action.');
        }
        $isSuperAdmin = $user->hasRole('super_admin');

        return view('users.index', compact('users', 'isSuperAdmin'));
    }

    public function create(): View
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            $roles = Role::pluck('name', 'id')->all();
            $admins = User::where('created_by_type', 'admin')->pluck('name', 'id')->all();
        } elseif ($user->hasRole('admin')) {
            $roles = Role::where('name', 'user')->pluck('name', 'id')->all();
            $admins = User::where('created_by_type', 'admin')->pluck('name', 'id')->all();
        } else {
            $roles = Role::where('name', '!=', 'super_admin')->pluck('name', 'id')->all();
            $admins = [];
        }

        return view('users.create', compact('roles', 'admins'));
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'roles' => 'required|exists:roles,id'
        ];

        $request->validate($rules);

        $params = $request->all();
        $role =  Role::find(Arr::get($params, 'roles'));
        $user = auth()->user();
        if ($user->hasRole('super_admin') && $role->name == 'user') {
            $rules['create_user'] = 'required|accepted';
            $request->validate($rules);
        }

        $params['password'] = Hash::make($params['password']);
        if ($user->hasRole('super_admin') && $role->name == 'user') {
            $params['created_by_id'] = $params['admin'];
            $params['created_by_type'] = 'admin';
        } else {
            $params['created_by_id'] = auth()->user()->id;
            $params['created_by_type'] = auth()->user()->roles->first()->name;
        }
        $user = User::create($params);
        $user->assignRole($role->name);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    public function show($id): View
    {
        $user = User::find($id);

        return view('users.show', compact('user'));
    }

    public function edit($id): View
    {
        $user = User::find($id);
        $authUser = auth()->user();
        $userRole = $user->roles->pluck('name', 'name')->all();
        $isSuperAdmin = $authUser->hasRole('super_admin');

        if ($authUser->hasRole('super_admin')) {
            $roles = Role::pluck('name', 'id')->all();
            $admins = User::where('created_by_type', 'admin')->pluck('name', 'id')->all();
        } elseif ($user->hasRole('admin')) {
            $roles = Role::where('name', 'user')->pluck('name', 'id')->all();
            $admins = User::where('created_by_type', 'admin')->pluck('name', 'id')->all();
        } else {
            $roles = Role::where('name', '!=', 'super_admin')->pluck('name', 'id')->all();
            $admins = [];
        }

        return view('users.edit', compact('user', 'roles', 'userRole', 'isSuperAdmin', 'admins'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'confirmed',
            'password_confirmation' => 'confirmed',
            'roles' => 'required'
        ];

        $request->validate($rules);

        $params = $request->all();
        $role =  Role::find(Arr::get($params, 'roles'));

        $authUser = auth()->user();
        if ($authUser->hasRole('super_admin') && $role->name == 'user') {
            $rules['create_user'] = 'required|accepted';
            $request->validate($rules);
        }


        if ($authUser->hasRole('super_admin') && $role->name == 'user') {
            $params['created_by_id'] = $params['admin'];
            $params['created_by_type'] = 'admin';
        } else {
            $params['created_by_id'] = auth()->user()->id;
            $params['created_by_type'] = auth()->user()->roles->first()->name;
        }

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($params['password']);
        } else {
            $input = Arr::except($params, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        ModelHasRole::where('model_id', $id)->delete();

        $user->assignRole($role->name);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }
}
