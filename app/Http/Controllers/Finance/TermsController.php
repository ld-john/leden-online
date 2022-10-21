<?php

namespace App\Http\Controllers\Finance;

use App\Finance\Term;
use App\Http\Controllers\Controller;
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

    public function show(Term $term)
    {
    }

    public function edit(Term $term)
    {
    }

    public function update(Request $request, Term $term)
    {
    }

    public function destroy(Term $term)
    {
    }
}
