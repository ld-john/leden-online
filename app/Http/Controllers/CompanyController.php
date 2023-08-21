<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $data = Company::where('id', '!=', 1)
            ->select(
                'id',
                'company_name',
                'company_email',
                'company_phone',
                'company_type',
            )
            ->get();

        return view('companies.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'company_name' => 'required',
                'company_address_1' => 'required',
                'company_type' => 'required',
                'company_city' => 'required',
                'company_email' => 'sometimes|email',
                'company_phone' => 'sometimes',
                'fleet_procure_member' => 'sometimes',
            ],
            [
                'company_name.required' =>
                    '<strong>Name</strong> is a required field',
                'company_address_1.required' =>
                    '<strong>Address</strong> is a required field',
                'company_email.email' =>
                    'Please enter a valid <strong>Email</strong>',
                'company_city.required' =>
                    '<strong>City</strong> is a required field',
                'company_type.required' =>
                    'Please Select a <strong>Type</strong>',
            ],
        );

        if ($request->input('fleet_procure_member') === 'on') {
            $fleet = true;
        } else {
            $fleet = false;
        }

        Company::create([
            'company_name' => $request->input('company_name'),
            'company_address1' => $request->input('company_address_1'),
            'company_address2' => $request->input('company_address_2'),
            'company_city' => $request->input('company_city'),
            'company_county' => $request->input('company_county'),
            'company_country' => $request->input('company_country'),
            'company_postcode' => $request->input('company_postcode'),
            'company_type' => $request->input('company_type'),
            'company_email' => $request->input('company_email'),
            'company_phone' => $request->input('company_phone'),
            'fleet_procure_member' => $fleet,
        ]);
        notify()->success('A new company has been added', 'Company Added');
        return redirect()->route('company_manager');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company $company
     * @return Application|Factory|View
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
     * @param Request $request
     * @param Company $company
     * @return RedirectResponse
     */
    public function update(Request $request, Company $company): RedirectResponse
    {
        if ($request->input('fleet_procure_member') === 'on') {
            $fleet = true;
        } else {
            $fleet = false;
        }
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
            'fleet_procure_member' => $fleet,
        ]);
        notify()->success(
            'The company information has been updated',
            'Company Updated',
        );
        return redirect()->route('company_manager');
    }
}
