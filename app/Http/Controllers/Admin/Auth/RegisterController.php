<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminInvitation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:admins,username',
            'role_id' => 'required',
            'password' => 'required|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        DB::beginTransaction();

        try {
            $adminInvite = AdminInvitation::where('id', $request->admin_invitation->id)
                ->where('used', false)
                ->firstOrFail();

            $path = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . "_" . Str::random(5) . "." . $file->getClientOriginalExtension();
                $path = $file->storeAs('user_images', $fileName, 'public');
                if (!$path) {
                    throw new \Exception('Failed to store the image.');
                }
            }

            $admin = new Admin();
            $admin->name = $request->name;
            $admin->email = $adminInvite->email;
            $admin->username = $request->username;
            $admin->role_id = $request->role_id;
            $admin->image = $path;
            $admin->password = Hash::make($request->password);
            $admin->save();

            $adminInvite->used = true;
            $adminInvite->save();

            DB::commit();
            return redirect()->route('admin.login');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back();
            // return back()->withErrors(['error' => 'Registration failed. ' . $e->getMessage()]);
        }
    }

    public function showRegisterForm(Request $request)
    {
        $invitation = $request->admin_invitation;
        return view('register', ['invitation' => $invitation]);
    }

    public function verifyToken(Request $request)
    {
        $token = $request->input('reg_token');
        return to_route('admin.register', ['reg_token' => $token]);
    }

    public function showVerifierForm()
    {
        return view('verifyUserReg');
    }
}
