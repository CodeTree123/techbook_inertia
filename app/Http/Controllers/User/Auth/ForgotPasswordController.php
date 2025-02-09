<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResets;
use Inertia\Inertia;

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

    public function __construct()
    {
        //parent::__construct();
        $this->middleware('guest');
    }


    public function showLinkRequestForm()
    {
        return Inertia::render('user/forgotPassword/ForgotPassword',[]);
    }

    public function sendResetCodeEmail(Request $request)
    {
        $request->validate([
            'value'=>'required|exists:users,username'
        ]);

        if(!verifyCaptcha()){
            $notify[] = ['error','Invalid captcha provided'];
            return back()->withNotify($notify);
        }
        
        $fieldType = $this->findFieldType();
        $user = User::where($fieldType, $request->value)->first();

        if (!$user) {
            $notify[] = ['error', 'Couldn\'t find any account with this information'];
            return back()->withNotify($notify);
        }

        PasswordReset::where('email', $user->email)->delete();
        $code = verificationCode(6);
        $password = new PasswordReset();
        $password->email = $user->email;
        $password->token = $code;
        $password->created_at = \Carbon\Carbon::now();
        $password->save();

        Mail::to($user->email)->send(new PasswordResets($code));
        $notify[] = ['success', 'Password reset email sent successfully'];
        return to_route('user.password.code.verify',$user->email)->withNotify($notify);
    }

    public function findFieldType()
    {
        $input = request()->input('value');

        $fieldType = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $input]);
        return $fieldType;
    }

    public function codeVerify($email){
        // $pageTitle = 'Verify Email';
        // $email = session()->get('pass_res_mail');
        // if (!$email) {
        //     $notify[] = ['error','Oops! session expired'];
        //     return to_route('user.password.request')->withNotify($notify);
        // }
        // return view('user.auth.passwords.code_verify',compact('pageTitle','email'));
        return Inertia::render('user/verifyEmail/VerifyEmail',[
            'email' => $email
        ]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'email' => 'required'
        ]);
        $code =  str_replace(' ', '', $request->code);

        if (PasswordReset::where('token', $code)->where('email', $request->email)->count() != 1) {
            return to_route('user.password.request')->with('verifyError', 'Verification code doesn\'t match');
        }
        // $notify[] = ['success', 'You can change your password.'];
        session()->flash('fpass_email', $request->email);
        return to_route('user.password.reset', $code)->with('success', 'You can change your password.');
    }

}
