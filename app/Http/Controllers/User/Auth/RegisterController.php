<?php

namespace App\Http\Controllers\User\Auth;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserInvitation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegForm(Request $request)
    {
        $invitation = $request->user_invitation;
        $pageTitle = "User Registration";
        return view('user.auth.register', compact('pageTitle', 'invitation'));
    }

    public function userStore(Request $request)
    {
        $validated = $request->validate([
            'firstname' => ['required', 'string', 'max:30'],
            'lastname' => ['required', 'string', 'max:30'],
            'username' => ['required', 'string', 'max:30', 'unique:users,username'],
            'mobile' => ['required', 'string', 'max:15'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:1024']
        ]);

        DB::beginTransaction();
        try {
            $userInvitation = UserInvitation::where('id', $request->user_invitation->id)
                ->where('used', false)
                ->firstOrFail();

            $user = new User();
            $user->firstname = $validated['firstname'];
            $user->lastname = $validated['lastname'];
            $user->username = $validated['username'];
            $user->mobile = $validated['mobile'];
            $user->email = $request->user_invitation->email;
            $user->password = Hash::make($validated['password']);

            $file = $request->file('image');
            if ($file) {
                $fileName = time() . "_" . Str::random(5) . "." . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/user_images', $fileName);
                $user->image_path = str_replace('public/', 'storage/', $path);
            }
            $user->save();

            $userInvitation->used = true;
            $userInvitation->save();

            $notify[] = ['success', 'You are now a registered user, now you can login into the system.'];
            DB::commit();
            return redirect()->route('user.login')->withNotify($notify);
        } catch (Exception $e) {
            DB::rollBack();
            $notify[] = ['error', 'Registration failed! ' . $e->getMessage()];
            return redirect()->back()->withNotify($notify);
        }
    }

    public function showVerifierForm()
    {
        $pageTitle = "Verify Token";
        return view('user.auth.verifyToken', compact('pageTitle'));
    }

    public function verifyToken(Request $request)
    {
        $token = $request->input('access_token');
        return to_route('user.register', ['access_token' => $token]);
    }
}
