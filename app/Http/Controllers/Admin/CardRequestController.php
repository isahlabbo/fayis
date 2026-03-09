<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CardRequest;
use App\Models\User;

class CardRequestController extends Controller
{
    public function index() {
        return view('admin.card.index');
    }

    public function view(User $user) {
        return view('admin.card.view', compact('user'));
    }

    public function markAsPrinted(CardRequest $cardRequest) {
        $cardRequest->update(['status'=>'printed']);
        return redirect()->route('admin.card.index')->withSuccess('The card mark printed');
    }
}
