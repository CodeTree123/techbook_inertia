<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = 'admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (in_array('__construct', get_class_methods(get_parent_class($this)))) {
            parent::__construct();
        }
        $this->middleware('admin.guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $pageTitle = "Admin Login";
        return view('admin.auth.login', compact('pageTitle'));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return auth()->guard('admin');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {

        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        $credential = $request->only('username', 'password');
        if ($this->guard('admin')->attempt($credential)) {
            if ($this->guard('admin')->user()->role_id == 1 || $this->guard('admin')->user()->role_id == 2) {
                return to_route('admin.dashboardNew')->with('message', 'Welcome to Dashboard');
            }elseif($this->guard('admin')->user()->role_id == 0){
                return to_route('admin.dashboard')->with('message', 'Welcome to Dashboard');
            }
        }
        return redirect("admin")->with('message', 'Login details are not valid');
    }

    public function logout(Request $request)
    {
        $this->guard('admin')->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect('/admin');
    }
}
