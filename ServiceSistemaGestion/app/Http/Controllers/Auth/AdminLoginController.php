<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AdminLoginController extends Controller
{

    public function __construct()
    {
        //configuramos que la autenticacion para todo ecepto para el logout
        //$this->middleware('guest:admin', ['except' => ['logout']]);
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
    	return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        //validate the form data
        $this->validate($request,[
          'email' => 'email|required|string',
          'password' => 'required|string|min:6'
        ]);

        //Attempt to log the user in
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
          //if successful, then redirect to their intended location
          return redirect()->intended(route('admin.dashboard'));
        }

        //if unsuccesful, the redirect back to the login with the form date
        return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect('/');
    }

}
