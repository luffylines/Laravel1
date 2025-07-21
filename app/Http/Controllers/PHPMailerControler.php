<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailerControler extends Controller
{
    public function getcontact()
    {
        return view('contact');
    }

    public function postcontact(Request $request)
{
    $validated = $request->validate([
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string',
    ]);

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = env('MAIL_HOST', 'smtp.gmail.com');
        $mail->SMTPAuth = true;
        $mail->Username = env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom($validated['email'], $validated['name']);
        $mail->addAddress('chba.aring.sjc@phinmaed.com'); // receiver
        $mail->isHTML(true);
        $mail->Subject = 'Contact Form Submission';

        $mail->Body = "
            <html>
            <body>
                <h3>Contact Form Submission</h3>
                <p><strong>Name:</strong> {$validated['name']}</p>
                <p><strong>Email:</strong> {$validated['email']}</p>
                <p><strong>Message:</strong><br>{$validated['message']}</p>
            </body>
            </html>
        ";

        $mail->send();

        return back()->with('success', 'Your message has been sent!');
    } catch (Exception $e) {
        Log::error('Contact mail error: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Message could not be sent.'])->withInput();
    }
}

    public function getlogin()
    {
        return view('login');
    }

    public function postlogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            return redirect()->route('dashboard')->with('success', 'Login successful!');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    public function getsignup()
    {
        return view('signup');
    }

    public function postsignup(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::create([
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']), // ✅ Secured password hashing
                'remember_token' => Str::random(60),
            ]);
        } catch (\Exception $e) {
            Log::error('User insert failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Signup failed. Please try again.'])->withInput();
        }

        // Send confirmation email
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST', 'smtp.gmail.com');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('chba.aring.sjc@phinmaed.com', 'New META');
            $mail->addAddress($validated['email']);
            $mail->addAddress('chba.aring.sjc@phinmaed.com'); // Optional admin copy
            $mail->isHTML(true);
            $mail->Subject = 'Signup Confirmation';

            $mail->Body = "
                <html>
                <head>
                <style>
                    body { font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; }
                    .container { max-width: 600px; margin: auto; background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
                    .header { text-align: center; margin-bottom: 20px; }
                    .footer { text-align: center; font-size: 12px; color: #777; margin-top: 20px; }
                    .footer a { color: #e74c3c; text-decoration: none; }
                </style>
                </head>
                <body>
                <div class='container'>
                    <div class='header'>
                        <img src='cid:logo_image' alt='Logo' width='150' />
                        <h2>Signup Confirmation</h2>
                    </div>
                    <p>Hello <strong>{$validated['firstname']}</strong>,</p>
                    <p>Your account has been successfully created with the following details:</p>
                    <ul>
                        <li><strong>Name:</strong> {$validated['firstname']},{$validated['lastname']}</li>
                        <li><strong>Email:</strong> {$validated['email']}</li>
                    </ul>
                    <p>Thank you for signing up!</p>
                    <div class='footer'>
                        <p>Need help? <a href='mailto:chba.aring.sjc@phinmaed.com'>Contact support</a></p>
                    </div>
                </div>
                </body>
                </html>
            ";

            $mail->addEmbeddedImage('C:/xampp/htdocs/Hospital Management System/images/logol.png', 'logo_image', 'logo.png');
            $mail->send();
        } catch (Exception $e) {
            Log::error('Signup mail error: ' . $e->getMessage());
        }

        return redirect()->route('login')->with('success', 'Signup successful! Please check your email.');
    }
}
