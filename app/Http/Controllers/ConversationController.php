<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ConversationController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $conversations = $user->conversations()->get();
        return view('pages.conversation',['user'=> $user,'conversations'=>$conversations]);
    }
}
