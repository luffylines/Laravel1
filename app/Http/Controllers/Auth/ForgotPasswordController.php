<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    | You can customize the password reset email by overriding the
    | sendResetLinkEmail method or by modifying the notification.
    | To connect to your custom reset password view or logic,
    | you may override the showLinkRequestForm or other methods.
    */

    use SendsPasswordResetEmails;
}
