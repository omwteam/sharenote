<?php
return [
    "pagesize"=> 15,
    "uploads" => env('APP_ENV') === 'production' ? 'http://172.28.2.228/stip/public/' : '/uploads'
];