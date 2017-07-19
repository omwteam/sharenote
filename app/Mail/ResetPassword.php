<?php

namespace App\Mail;

use App\ForgetToken;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * token信息
     * @var mixed
     */
    protected $token;
    /**
     * 用户邮箱
     * @var mixed
     */
    protected $email;
    /**
     * ResetPassword constructor.
     * @param ForgetToken $forgetToken
     */
    public function __construct(ForgetToken $forgetToken)
    {
        //
        $this->token = $forgetToken->token;
        $this->email = $forgetToken->email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'),config('mail.from.name'))
                ->subject('STIP平台 一 重置密码！！!')
            ->view('emails.forget')->with([
                'token' => bcrypt($this->token),
                'email' => $this->email
            ]);
    }
}
