<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;
use App\User;
use Helper;
use Auth;
use DB;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function showProfile() {
        $company_name = DB::table('company')
            ->select('company_name')
            ->where('id', Auth::user()->company_id)
            ->first();

        $company_info = DB::table('company')
            ->select('company_address1', 'company_address2', 'company_city', 'company_county', 'company_country', 'company_postcode')
            ->where('id', Auth::user()->company_id)
            ->first();

        return view('profile', [
            'company_name' => $company_name,
            'company_info' => $company_info,
        ]);
    }

    public function executeUpdateProfile(Request $request) {
        $user = Auth::user();

        if ($request->get('password') == null) {

            $user->firstname = $request->get('firstname');
            $user->lastname = $request->get('lastname');
            $user->phone = $request->get('phone');
            $user->save();

            return view('profile')->with('successMsg', 'Your profile has been updated!');

        } else {

            if ($request->get('password') != $request->get('password_confirmation')) {

                return view('profile')->with('errorMsg', 'Your new passwords do not match! Please try again.');
        
            } else {
            
                $user->firstname = $request->get('firstname');
                $user->lastname = $request->get('lastname');
                $user->phone = $request->get('phone');
                $user->password = bcrypt($request->get('password'));
                $user->save();

                return view('profile')->with('successMsg', 'Your profile and password has been updated!');

            }

        }
    }

    public function showUserManager(Request $request) {
        if (Helper::roleCheck(Auth::user()->id)->role != 'admin') {
            return view('unauthorised');
        }

        if ($request->ajax()) {
            $data = User::where('users.id', '!=', Auth::user()->id)
            ->select('users.id', 'users.firstname', 'users.lastname', 'users.email', 'company.company_name as company', 'users.role', 'users.is_deleted')
            ->leftJoin('company', 'company.id', 'users.company_id')
            ->get();

            return Datatables::of($data)
                ->addColumn('action', function($row){
                    $btn = '<a href="/user-management/edit/' . $row->id . '" class="edit btn btn-warning"><i class="fas fa-edit"></i>Edit</a>';

                    if ($row->is_deleted == null) {
                        $btn .= '<a href="/user-management/disable/' . $row->id . '" class="disable btn btn-danger"><i class="fas fa-times"></i> Disable</a>';
                    } else {
                        $btn .= '<a href="/user-management/disable/' . $row->id . '" class="disable btn btn-success"><i class="fas fa-check"></i> Enable</a>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users');
    }

    public function showAddUser() {
        $companies = DB::table('company')
            ->select('id', 'company_name')
            ->get();

        return view('add-user', [
            'companies' => $companies,
        ]);
    }

    public function executeAddUser(Request $request) {
        $validatedData = $request->validate([
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
            'password' => bcrypt($request->input('password'))
        ]);

        return redirect()->route('user_manager')->with('successMsg', 'A new user has been created');
    }

    public function showEditUser(Request $request) {
        if (Helper::roleCheck(Auth::user()->id)->role != 'admin') {
            return view('unauthorised');
        }

        $user_id = $request->route('user_id');
        $user = User::where('id', $user_id)->first();

        $companies = DB::table('company')
            ->select('id', 'company_name')
            ->get();

        return view('edit-user', [
            'user' => $user,
            'companies' => $companies,
        ]);
    }

    public function executeEditUser(Request $request) {
        $user = User::select('email')
            ->where('id', $request->route('user_id'))
            ->first();

        if ($user->email != $request->input('email')) {
            $validatedData = $request->validate([
                'email' => 'required|unique:users|max:191',
            ]);
        }

        User::where('id', $request->route('user_id'))
            ->update([
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'company_id' => $request->input('company_id'),
                'role' => $request->input('role'),

            ]);

        return redirect()->route('user_manager')->with('successMsg', 'The user has been updated');
    }
    
    public function toggleDisabled(Request $request) {
        if (Helper::roleCheck(Auth::user()->id)->role != 'admin') {
            return view('unauthorised');
        }

        $user_id = $request->route('user_id');
        $user = User::where('id', $user_id)->first();

        if ($user->is_deleted == null) {
            User::where('id', $user_id)
                ->update([
                    'is_deleted' => 1,
                ]);

            $message = $user->firstname . ' ' . $user->lastname . '\'s account has been disabled';
        } else {
            User::where('id', $user_id)
                ->update([
                    'is_deleted' => null,
                ]);

            $message = $user->firstname . ' ' . $user->lastname . '\'s account has been enabled';
        }

        return redirect()->route('user_manager')->with('successMsg', $message);
    }

    public function showCompanies(Request $request) {
        if (Helper::roleCheck(Auth::user()->id)->role != 'admin') {
            return view('unauthorised');
        }

        if ($request->ajax()) {
            $data = DB::table('company')
                ->where('id', '!=', 1)
                ->select('id', 'company_name', 'company_email', 'company_phone', 'company_type')
                ->get();

            return Datatables::of($data)
                ->addColumn('action', function($row){
                    $btn = '<a href="/companies/edit/' . $row->id . '" class="edit btn btn-warning"><i class="fas fa-edit"></i>Edit</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('companies');
    }

    public function showAddCompany() {
        if (Helper::roleCheck(Auth::user()->id)->role != 'admin') {
            return view('unauthorised');
        }

        return view('add-company');
    }

    public function executeAddCompany(Request $request) {
        DB::table('company')
            ->insert([
                'company_name' => $request->input('company_name'),
                'company_address1' => $request->input('company_address1'),
                'company_address2' => $request->input('company_address2'),
                'company_city' => $request->input('company_city'),
                'company_county' => $request->input('company_county'),
                'company_country' => $request->input('company_country'),
                'company_postcode' => $request->input('company_postcode'),
                'company_type' => $request->input('company_type'),
                'company_email' => $request->input('company_email'),
                'company_phone' => $request->input('company_phone'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        return redirect()->route('company_manager')->with('successMsg', 'A new company has been added');
    }

    public function showEditCompany(Request $request) {
        if (Helper::roleCheck(Auth::user()->id)->role != 'admin') {
            return view('unauthorised');
        }
        
        $company = DB::table('company')
            ->where('id', $request->route('company_id'))
            ->first();

        return view('edit-company', [
            'company' => $company,
        ]);
    }

    public function executeEditCompany(request $request) {
        DB::table('company')
            ->where('id', $request->route('company_id'))
            ->update([
                'company_name' => $request->input('company_name'),
                'company_address1' => $request->input('company_address1'),
                'company_address2' => $request->input('company_address2'),
                'company_city' => $request->input('company_city'),
                'company_county' => $request->input('company_county'),
                'company_country' => $request->input('company_country'),
                'company_postcode' => $request->input('company_postcode'),
                'company_type' => $request->input('company_type'),
                'company_email' => $request->input('company_email'),
                'company_phone' => $request->input('company_phone'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        return redirect()->route('company_manager')->with('successMsg', 'The company information has been updated');
    }
}
