<?php

namespace App\Http\Controllers;

use App\Mail\GenericEmail;
use App\Models\AdminInvitation;
use App\Models\UserInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Exception;

class AdminInvitationController extends Controller
{
    public function index()
    {
        $pageTitle = "Admin Invitation";
        return view('admin.admin-invite', compact('pageTitle'));
    }

    public function invite(Request $request)
    {
        $retries = $request->input('retries', 0);
        $request->validate([
            'email' => 'required|email',
        ]);

        $token = $this->generateUniqueTokenForAdmin();

        $mail = [
            'to' => $request->email,
            'body' => "Congratulations! You received a admin registration invitation from Tech Yeah for the TechBook Portal.",
            'token' => $token,
            'subject' => 'Admin registration invitation',
            'sender' => 'info@techyeahinc.com',
            'link' => 'https://www.techbookportal.com/admin/register',
        ];

        $notify = [];

        try {
            $adminInvite = new AdminInvitation;
            $adminInvite->email = $request->email;
            $adminInvite->token = $token;
            $adminInvite->expire_at = now()->addMinutes(30);
            $adminInvite->used = false;
            $adminInvite->save();

            if ($adminInvite->exists) {
                Mail::to($request->email)->send(new GenericEmail($mail));
                $notify[] = ['success', 'Invitation created and email sent successfully!'];
            } else {
                $notify[] = ['error', 'Failed to create admin invitation.'];
            }
        } catch (Exception $e) {
            if ($e->getCode() === '23000' && $retries < 1) {
                $data = "Admin";
                $this->removeDuplicate($data, $request->email);
                return $this->invite($request->merge(['retries' => $retries + 1]));
            }
            $notify[] = ['error', 'Admin invitation failed: ' . $e->getMessage()];
        }

        return to_route('admin.invite.index')->withNotify($notify);
    }

    public function inviteUser(Request $request)
    {
        $retries = $request->input('retries', 0);
        $request->validate([
            'email' => 'required|email',
        ]);

        $token = $this->generateUniqueTokenForUser();

        $mail = [
            'to' => $request->email,
            'body' => "Congratulations! You received a user registration invitation from Tech Yeah for the TechBook Portal.",
            'token' => $token,
            'subject' => 'User Registration Invitation',
            'sender' => 'info@techyeahinc.com',
            'link' => 'https://www.techbookportal.com/user/token/verify/view',
        ];

        $notify = [];

        try {
            $userInvitation = new UserInvitation;
            $userInvitation->email = $request->email;
            $userInvitation->token = $token;
            $userInvitation->expire_at = now()->addMinutes(30);
            $userInvitation->used = false;
            $userInvitation->save();

            if ($userInvitation->exists) {
                Mail::to($request->email)->send(new GenericEmail($mail));
                $notify[] = ['success', 'Invitation created and email sent successfully!'];
            } else {
                $notify[] = ['error', 'Failed to create admin invitation.'];
            }
        } catch (Exception $e) {
            if ($e->getCode() === '23000' && $retries < 1) {
                $data = "User";
                $this->removeDuplicate($data, $request->email);
                return $this->inviteUser($request->merge(['retries' => $retries + 1]));
            }
            $notify[] = ['warning', 'User invitation failed: ' . $e->getMessage()];
        }

        return to_route('user.invite.index')->withNotify($notify);
    }

    private function removeDuplicate($flag, $data)
    {
        if ($flag === "User") {
            UserInvitation::where('email', $data)->delete();
        } else {
            AdminInvitation::where('email', $data)->delete();
        }
    }

    private function generateUniqueTokenForAdmin()
    {
        do {
            $token = Str::random(32);
        } while (AdminInvitation::where('token', $token)->exists());
        return $token;
    }

    private function generateUniqueTokenForUser()
    {
        do {
            $token = Str::random(32);
        } while (UserInvitation::where('token', $token)->exists());
        return $token;
    }

    public function userIndex()
    {
        $pageTitle = "User Invitation";
        return view('admin.userInvite', compact('pageTitle'));
    }
}
