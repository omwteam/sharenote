<?php

namespace App\Http\Controllers\Notes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    //
    public function index()
    {
        return view('doc.index');
    }
    public function share()
    {
        return view('doc.share');
    }
}
