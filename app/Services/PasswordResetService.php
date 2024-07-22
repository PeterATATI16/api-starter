<?php

namespace App\Services;

use App\Mail\PasswordResetConfirmationMail;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PasswordResetService
{
    public function sendResetCode($email)
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return;
            ;
        }

        $code = Str::random(6);
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $code,
                'created_at' => Carbon::now()
            ]
        );

        Mail::to($email)->send(new ResetPasswordMail($code));
        return true;
    }

    public function validateResetCode($email, $code)
    {
        $reset = DB::table('password_resets')->where('email', $email)->where('token', $code)->first();
        return $reset && Carbon::parse($reset->created_at)->addMinutes(30)->isFuture();
    }

    public function resetPassword($email, $code, $newPassword)
    {
        if (!$this->validateResetCode($email, $code)) {
            return false;
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return false;
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        DB::table('password_resets')->where('email', $email)->delete();
        Mail::to($email)->send(new PasswordResetConfirmationMail($user));
        return $user;
    }
}
