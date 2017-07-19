<?php

namespace App\Http\Controllers;

use App\ForgetToken;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Mail;
use App\Notes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    //
    public function log ()
    {
        DB::connection()->enableQueryLog();
        $log = DB::getQueryLog();
        dd($log);
    }
    public function test ()
    {


    }
}
