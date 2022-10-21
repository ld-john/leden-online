<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\Term;
use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function index()
    {
        return view('finance.terms.index');
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(\App\Models\Finance\Term $term)
    {
    }

    public function edit(Term $term)
    {
    }

    public function update(Request $request, \App\Models\Finance\Term $term)
    {
    }

    public function destroy(\App\Models\Finance\Term $term)
    {
    }
}
