<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\subscribersDataTable;
use App\Http\Controllers\Controller;
use App\Mail\NewsLetter;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Mail;

class NewsLetterController extends Controller
{
    public function index(subscribersDataTable $dataTable)
    {
        return $dataTable->render('admin.news-letter.index');
    }


    public function sendNewsLetter(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'subject' => ['required', 'max:255'],
            'message' => ['required'],
        ]);

        $subscribers = Subscriber::pluck('email')->toArray();

        //? send mail meessage to subscribers
        Mail::to($subscribers)->send(new NewsLetter(
            $request->subject,
            $request->message
        ));

        toastr()->success("News letter sent successfully");

        return redirect()->back();
    }
}
