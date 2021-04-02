<?php

namespace App\Jobs;

use App\User;

class SendRegisterEmail extends Job
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        $user = $this->user;
        $text = strtr('Hi :name! Thank you for signing up!', [':name' => $user->name]);
        app('mailer')->raw($text, function ($message) use ($user) {
            $message->to($user->email);
        });
    }
}
