<?php

namespace App\Http\Controllers;

use App\Models\TacticalOffer;

class TacticalOfferController extends Controller
{
    public function index()
    {
        return view('tactical-offer.index', [
            'title' => 'Tactical Offers',
            'active_page' => 'tactical-offers',
        ]);
    }

    public function create() {
        return view('tactical-offer.create');
    }

    public function edit(TacticalOffer $tacticalOffer) {
        return view('tactical-offer.edit', compact('tacticalOffer'));
    }

    public function delete(TacticalOffer $tacticalOffer) {
        $tacticalOffer->delete();
        notify()->success(
            'Tactical Offer ' .
            $tacticalOffer->name .
            ' deleted successfully - ',
            'Tactical Offer Deleted',
        );
        return redirect()->back();
    }
}
