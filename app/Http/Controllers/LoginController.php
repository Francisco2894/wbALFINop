<?php

namespace wbALFINop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => '1'])) {
            // Authentication passed...
            return redirect()->route('devengo.index');
        }
        return back()->withInput()->with(['error' =>'Acceso Denegado']);
    }
}
