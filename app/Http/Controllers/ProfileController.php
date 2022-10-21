<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showProfile()
    {
        $company = Company::where(
            'id',
            Auth()->user()->company_id,
        )->firstOrFail();

        return view('users.profile', [
            'company' => $company,
        ]);
    }

    public function executeUpdateProfile(Request $request)
    {
        $user = Auth::user();

        if ($request->get('password') == null) {
            $user->firstname = $request->get('firstname');
            $user->lastname = $request->get('lastname');
            $user->phone = $request->get('phone');
            $user->save();
            notify()->success(
                'Your profile has been updated!',
                'Profile Updated',
            );
        } else {
            if (
                $request->get('password') !=
                $request->get('password_confirmation')
            ) {
                notify()->error(
                    'Your new passwords do not match! Please try again.',
                );
            } else {
                $user->firstname = $request->get('firstname');
                $user->lastname = $request->get('lastname');
                $user->phone = $request->get('phone');
                $user->password = bcrypt($request->get('password'));
                $user->save();
                notify()->success(
                    'Your profile and password has been updated!',
                    'Profile Updated',
                );
            }
        }
        return view('users.profile');
    }

    public function showUserManager(Request $request)
    {
        $data = User::where('users.id', '!=', Auth::user()->id)
            ->select(
                'id',
                'firstname',
                'lastname',
                'email',
                'company_id',
                'role',
                'is_deleted',
            )
            ->with('company:id,company_name')
            ->get();

        if ($request->ajax()) {
            $data = User::where('users.id', '!=', Auth::user()->id)
                ->select(
                    'id',
                    'firstname',
                    'lastname',
                    'email',
                    'company_id',
                    'role',
                    'is_deleted',
                )
                ->with('company')
                ->get();

            return Datatables::of($data)
                ->addColumn('action', function ($row) {
                    $btn = '';

                    if ($row->is_deleted == null) {
                        $btn .=
                            '<a href="/user-management/edit/' .
                            $row->id .
                            '" class="edit btn btn-warning"><i class="fas fa-edit"></i> Edit</a>';
                        $btn .=
                            '<a href="/user-management/disable/' .
                            $row->id .
                            '" class="disable btn btn-danger"><i class="fas fa-times"></i> Disable</a>';
                    } else {
                        $btn .=
                            '<a href="/user-management/disable/' .
                            $row->id .
                            '" class="disable btn btn-success"><i class="fas fa-check"></i> Enable</a>';
                        $btn .=
                            '<a href="/user-management/delete/' .
                            $row->id .
                            '" class="disable btn btn-danger"><i class="fas fa-trash"></i> Delete</a>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users.index', ['data' => $data]);
    }

    public function showAddUser()
    {
        $companies = Company::select('id', 'company_name')->get();

        return view('users.create', [
            'companies' => $companies,
        ]);
    }

    public function executeAddUser(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users|max:191',
            'password' => 'required|confirmed',
        ]);

        User::create([
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'company_id' => $request->input('company_id'),
            'role' => $request->input('role'),
            'password' => bcrypt($request->input('password')),
        ]);

        notify()->success('A new user has been created', 'User Created');

        return redirect()->route('user_manager');
    }

    public function showEditUser(User $user)
    {
        $companies = Company::select('id', 'company_name')->get();

        return view('users.edit', [
            'user' => $user,
            'companies' => $companies,
        ]);
    }

    public function storeEditUser(User $user, Request $request)
    {
        $request->validate([
            'email' => 'required|max:191',
            Rule::unique('users')->ignore($user->id),
        ]);

        $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'company_id' => $request->company_id,
        ]);
        notify()->success('The user has been updated', 'User Updated');
        return redirect()->route('user_manager');
    }

    public function toggleDisabled(User $user)
    {
        if ($user->is_deleted == null) {
            $user->update([
                'is_deleted' => 1,
            ]);

            $message =
                $user->firstname .
                ' ' .
                $user->lastname .
                '\'s account has been disabled';
        } else {
            $user->update([
                'is_deleted' => null,
            ]);

            $message =
                $user->firstname .
                ' ' .
                $user->lastname .
                '\'s account has been enabled';
        }
        notify()->success($message);
        return redirect()->route('user_manager');
    }

    public function delete(User $user)
    {
        $user->delete();
        notify()->success(
            $user->firstname .
                ' ' .
                $user->lastname .
                '\'s account has been deleted',
        );
        return redirect()->route('user_manager');
    }
}
