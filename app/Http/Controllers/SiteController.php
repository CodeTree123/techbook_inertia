<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Page;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cookie;
use PDF;

class SiteController extends Controller
{

    public function placeholderImage($size = null)
    {
        $imgWidth = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font/RobotoMono-Regular.ttf');
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 175, 175, 175);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function maintenance()
    {
        $pageTitle = 'Maintenance Mode';
        $general = gs();
        if ($general->maintenance_mode == Status::DISABLE) {
            return to_route('home');
        }
        $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
        return view('templates.basic.maintenance', compact('pageTitle', 'maintenance'));
    }

    public function policyPages($slug, $id)
    {
        $policy = Frontend::where('id', $id)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_values->title;
        return view('templates.basic.policy', compact('policy', 'pageTitle'));
    }

    public function contact()
    {
        $pageTitle = "Contact Us";
        return view('contact', compact('pageTitle'));
    }

    public function contactSubmit(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        // if(!verifyCaptcha()){
        //     $notify[] = ['error','Invalid captcha provided'];
        //     return back()->withNotify($notify);
        // }

        $request->session()->regenerateToken();

        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = Status::PRIORITY_MEDIUM;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = Status::TICKET_OPEN;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'A new support ticket has opened ';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function test()
    {
        $pageTitle = "UUU";
        return view('test', compact('pageTitle'));
    }

    public function pdfWorkOrderUser($id)
    {

        $pageTitle = "Download Work Order";
        $views = WorkOrder::with('site', 'customer', 'employee','schedules')->find($id);
        $scheduled = $views->schedules()->latest()->first();
        $imageFileNames = $views->pictures ? json_decode($views->pictures) : [];
        $pdf = PDF::loadView('user.pdf.work_order', compact('pageTitle', 'views', 'imageFileNames'))->setOptions(['defaultFont' => 'sans-serif']);
        $pdf->setPaper('A4', 'portrait');
        $customerCompanyName = @$views->customer->company_name;
        $fileName = $customerCompanyName . '_Work_Order.pdf';

        return $pdf->download($fileName);
    }
    
    public function pdfWorkOrderUserView($id)
    {
        $pageTitle = "View Pdf";
        $views = WorkOrder::with('site', 'customer', 'employee','schedules')->find($id);
        $scheduled = $views->schedules()->latest()->first();
        $imageFileNames = json_decode($views->pictures);
        return view('user.pdf.view', compact('pageTitle', 'views', 'imageFileNames'));
    }

    public function deleteImage(Request $request)
    {
        $request->validate([
            'imageName' => 'required|string',
            'workOrderId' => 'required|integer', // Assuming workOrderId is available to identify the record
        ]);

        $imageName = $request->input('imageName');
        $workOrderId = $request->input('workOrderId');
        $filePath = public_path('imgs/' . $imageName);

        $update = WorkOrder::find($workOrderId);

        if ($update) {
            $existingPictures = json_decode($update->pictures, true) ?? [];
            $key = array_search($imageName, $existingPictures);
            if ($key !== false) {
                unset($existingPictures[$key]);
            }
            $update->pictures = json_encode(array_values($existingPictures));
            $update->save();
            if (!in_array($imageName, $existingPictures) && File::exists($filePath)) {
                File::delete($filePath);
            }

            return redirect()->back()->with('success', 'Image deleted successfully.');
        }

        return redirect()->back()->with('error', 'Record not found.');
    }
}
