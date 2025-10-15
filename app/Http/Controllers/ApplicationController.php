<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Token;
use App\Models\Application;
use App\Models\AcademicSession;
use App\Services\HasApplication;

class ApplicationController extends Controller
{
    use HasApplication;
    
    public function create($tokenId)
    {
        return view('application.create',['token'=>Token::find($tokenId)]);
    }

    public function register(Request $request, $tokenId)
    {

        $data = $request->all();

        $this->registerThisApplication($data);

        return redirect()->route('dashboard')->withSuccess('Application Registred');
    }

    public function index($sessionId)
    {
        $session = AcademicSession::find($sessionId);
        if(count($session->applications) > 0){
            return view('application.index',['session'=>AcademicSession::find($sessionId)]);
        }
        return redirect()->route('dashboard')->withError('No Applications Found');
    }

    public function view($applicationId)
    {
        return view('application.view',['application'=>Application::find($applicationId)]);
    }
}
