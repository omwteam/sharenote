<?php

namespace App\Http\Controllers\Notes;

use App\Http\Controllers\BaseController;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Mail;
use App\ForgetToken;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    //
    public function getModify()
    {
        return view('auth.modify');
    }
    public function postModify(Request $request)
    {

        $oldpassword = $request->input('oldpassword');
        $password = $request->input('password');
        $data = $request->all();
        dump($data);
        $rules = [
            'oldpassword'=>'required|between:6,20',
            'password'=>'required|between:6,20|confirmed',
        ];
        $messages = [
            'required' => '密码不能为空',
            'between' => '密码必须是6~20位之间',
            'confirmed' => '新密码和确认密码不匹配'
        ];
        $validator = Validator::make($data, $rules, $messages);
        $user = Auth::user();
        $validator->after(function($validator) use ($oldpassword, $user) {
            if (!Hash::check($oldpassword, $user->password)) {
                $validator->errors()->add('oldpassword', '原密码错误');
            }
        });
        if ($validator->fails()) {
            return back()->withErrors($validator);  //返回一次性错误
        }
        $user->password = bcrypt($password);
        $user->save();
        Auth::logout();  //更改完这次密码后，退出这个用户
        return redirect('/login');
    }

    /**
     * 验证登录
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkLogin()
    {
        return Auth::check() ? $this->ajaxSuccess('正处于登录状态',user()) : $this->ajaxError('登录过期');
    }

    /**
     * 忘记密码-验证邮箱
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkEmail ()
    {
        return view('auth.email');
    }

    /**
     * 处理忘记密码
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleEmail (Request $request)
    {
        // 验证传输过来的邮箱
        $this->validate($request,['email'=>'required|email']);

        $email = $request->input('email');
        //验证邮箱是否存在
        $flag = User::where('email',$email)->count();
        if ($flag < 1) {
            return back()->withErrors(['error'=>'邮箱未注册！']);
        }
        //生成token,并插入数据库
        $forgetToken = ForgetToken::create(['email'=>$email,'token'=> str_random(32),'expired_time'=> time() + 30*60]);
        // 发送邮件
        $this->sendEmail($email,$forgetToken);
        //
        return redirect(route('login'));
    }

    /**
     * 显示重置密码页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getForget (Request $request)
    {
        $params = $request->input();
        return view('auth.reset',['token' => $params['token'],'email'=>$params['email']]);
    }

    /**
     * 处理显示页面
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postForget (Request $request)
    {
        // 验证传输过来的邮箱
        $rules = [
            'email'=>'required|email',
            'password'=>'required|between:6,20|confirmed',
        ];
        $messages = [
            'required' => '密码不能为空',
            'between' => '密码必须是6~20位之间',
            'confirmed' => '新密码和确认密码不匹配'
        ];
        $this->validate($request,$rules,$messages);

        $params = $request->input();
        $data = ForgetToken::where('email',$params['email'])->latest()->first();
        if ( !(Hash::check($data->token,$params['token']) && $data->expired_time > time()) ) {
            return back()->withErrors(['error'=>'页面过期！']);
        }
        $flag = User::where('email',$params['email'])->update(['password'=>bcrypt($params['password'])]);
        if (!$flag) {
            return back()->withErrors(['error'=>'未知错误，请重试！']);
        }
        Auth::logout();  //更改完这次密码后，退出这个用户
        return redirect('/login');

    }

    /**
     * 发送邮件
     * @param $email
     * @param $tokenModel
     * @return mixed
     */
    public function sendEmail ($email,$tokenModel)
    {
        return Mail::to($email)->send(new ResetPassword($tokenModel));
    }
}
