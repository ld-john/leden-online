<?php

namespace App\Http\Controllers;

use App\Company;
use App\OldCompany;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    	$data = Company::where('id', '!=', 1)
		    ->select('id', 'company_name', 'company_email', 'company_phone', 'company_type')
		    ->get();
	    if ($request->ajax()) {
		    $data = Company::where('id', '!=', 1)
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

	    return view('companies.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
	    return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    Company::create([
			    'company_name' => $request->input('company_name'),
			    'company_address1' => $request->input('company_address1'),
			    'company_address2' => $request->input('company_address2'),
			    'company_city' => $request->input('company_city'),
			    'company_county' => $request->input('company_county'),
			    'company_country' => $request->input('company_country'),
			    'company_postcode' => $request->input('company_postcode'),
			    'company_type' => $request->input('company_type'),
			    'company_email' => $request->input('company_email'),
			    'company_phone' => $request->input('company_phone')
		    ]);

	    return redirect()->route('company_manager')->with('successMsg', 'A new company has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
	    return view('companies.edit', [
		    'company' => $company,
	    ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Company $company)
    {
	    $company->update([
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
		    ]);

	    return redirect()->route('company_manager')->with('successMsg', 'The company information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //
    }


}
