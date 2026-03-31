<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CardRequest;
use App\Models\User;
use App\Services\Upload\FileUpload;

class CardRequestController extends Controller
{
    use FileUpload;

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

    public function update(Request $request, $cardRequestId){
        $request->validate([
            'section'=>'required',
            'position'=>'required',
            'reason'=>'required|string|max:1000',
            'signature'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            
        ]);

        $cardRequest = CardRequest::findOrFail($cardRequestId);

        $cardRequest = $cardRequest->update([
            'section_id'=>$request->section,
            'position'=>$request->position,
            'reason'=>$request->reason,
        ]);

        if($request->hasFile('signature')){
            $file = $request->file('signature');
            $location = 'profile/signatures/';
            $this->updateFile($cardRequest, 'signature', $file, $location);
        }

        return redirect()->back()->withSuccess('ID Card Updated');
        
    }

    public function delete(CardRequest $cardRequest){
        $cardRequest->delete();
        return redirect()->route('admin.card.index')->withSuccess('Card request deleted successfully');
    }
}
