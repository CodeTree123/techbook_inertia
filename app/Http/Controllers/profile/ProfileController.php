<?php

namespace App\Http\Controllers\profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function getAdminProfile()
    {
        if (auth('admin')->check()) {
            $admin = auth('admin')->user();
            $imageUrl = $admin->image;
            $imageUrl = asset('storage/' . $imageUrl);
            $no_image_url = asset('storage/user_images/No_Image_Available.jpg');
            if ($admin->image == null) {
                $imageUrl = $no_image_url;
            }
            $admin->image = $imageUrl;
            return response()->json($admin);
        }
    }
}
