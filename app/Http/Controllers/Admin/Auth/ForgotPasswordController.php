<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\Admin;
use App\Models\AdminPasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResets;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
        $this->middleware('admin.guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        $pageTitle = 'Account Recovery';
        return view('admin.auth.passwords.email', compact('pageTitle'));
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('admins');
    }

    public function sendResetCodeEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        // if(!verifyCaptcha()){
        //     $notify[] = ['error','Invalid captcha provided'];
        //     return back()->withNotify($notify);
        // }

        $admin = Admin::where('email', $request->email)->first();
        if (!$admin) {
            return back()->withErrors(['Email Not Available']);
        }

        $code = verificationCode(6);
        $adminPasswordReset = new AdminPasswordReset();
        $adminPasswordReset->email = $admin->email;
        $adminPasswordReset->token = $code;
        $adminPasswordReset->created_at = date("Y-m-d h:i:s");
        $adminPasswordReset->save();

            // Send the email
        Mail::to($admin->email)->send(new PasswordResets($code));

        return to_route('admin.password.code.verify');
    }

    public function codeVerify(){
        $pageTitle = 'Verify Code';
        // $email = session()->get('pass_res_mail');
        // if (!$email) {
        //     $notify[] = ['error','Oops! session expired'];
        //     return to_route('admin.password.reset')->withNotify($notify);
        // }
        return view('admin.auth.passwords.code_verify', compact('pageTitle'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required']);
        $notify[] = ['success', 'You can change your password.'];
        $code = str_replace(' ', '', $request->code);
        $admin = AdminPasswordReset::where('token', $request->code)->first();
        if(!$admin){
            $notify[] = ['success', 'did not match code'];
        }
        return to_route('admin.password.reset.form', $code)->withNotify($notify);
    }
}
