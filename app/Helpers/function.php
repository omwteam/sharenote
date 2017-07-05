<?php
/**
 * Created by PhpStorm.
 * User: freddy
 * Date: 2017/6/24
 * Time: 11:25
 */


if(! function_exists('user')){

    /**
     * @param null $driver
     * @return mixed
     */
    function user($driver = null){
        if($driver){
            return app('auth')->guard($driver)->user();
        }
        return app('auth')->user();
    }
}