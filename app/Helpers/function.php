<?php
/**
 * Created by PhpStorm.
 * User: freddy
 * Date: 2017/6/24
 * Time: 11:25
 */


if (!function_exists('checkLogin')) {
    function checkLogin() {
        $log_token = $_COOKIE['log_token'];
        // 查看是否存在log_token cookie
        if (!isset($log_token)) {
            return redirect('/login');
        }

        $tokenData =  \Illuminate\Support\Facades\DB::table('users_token')->where('token',$log_token)->select('uid','token_expired')->first();
//        dump($tokenData->token_expired);
        // 如果过期了跳转到登录
        if (time() > $tokenData->token_expired) {
            return redirect('/login');
        }
    }
}

if (!function_exists('checkLogin')) {
    function checkLogin() {
        $log_token = $_COOKIE['log_token'];
        // 查看是否存在log_token cookie
        if (!isset($log_token)) {
            return redirect('/login');
        }

        $tokenData =  \Illuminate\Support\Facades\DB::table('users_token')->where('token',$log_token)->select('uid','token_expired')->first();
//        dump($tokenData->token_expired);
        // 如果过期了跳转到登录
        if (time() > $tokenData->token_expired) {
            return redirect('/login');
        }
    }
}