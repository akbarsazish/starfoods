<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class ContactUs extends Controller{
    public function index(Request $request)
    {
        return view('messages.contact');
    }
}