<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
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
        'contact_number' => 'required|digits:10',
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

        $fullName = $validated['firstname'] . ' ' . $validated['lastname'];
        
        // Set the user's email as sender (for reply purposes)
        $mail->setFrom($validated['email'], $fullName);
        
        // Send email ONLY to admin - NOT to the user who filled out the form
        $mail->addAddress('chba.aring.sjc@phinmaed.com'); // Admin email receiver
        
        $mail->isHTML(true);
        $mail->Subject = 'Contact Form Submission';

        $mail->Body = "
            <html>
            <body>
                <h3>Contact Form Submission</h3>
                <p><strong>First Name:</strong> {$validated['firstname']}</p>
                <p><strong>Last Name:</strong> {$validated['lastname']}</p>
                <p><strong>Email:</strong> {$validated['email']}</p>
                <p><strong>Contact Number:</strong> {$validated['contact_number']}</p>
                <p><strong>Message:</strong><br>{$validated['message']}</p>
            </body>
            </html>
        ";

        $mail->send();

        // Send SMS notification to admin
        $this->sendSMSNotification($validated);

        return back()->with('success', 'Your message has been sent!');
    } catch (Exception $e) {
        Log::error('Contact mail error: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Message could not be sent.'])->withInput();
    }
}

    /**
     * Send SMS notification to admin when contact form is submitted
     */
    private function sendSMSNotification($contactData)
    {
        try {
            $adminNumber = '9278856264'; // Your admin number
            $fullName = $contactData['firstname'] . ' ' . $contactData['lastname'];
            
            // Create SMS message
            $smsMessage = "New Contact Form Submission!\n";
            $smsMessage .= "Name: {$fullName}\n";
            $smsMessage .= "Email: {$contactData['email']}\n";
            $smsMessage .= "Phone: {$contactData['contact_number']}\n";
            $smsMessage .= "Message: " . substr($contactData['message'], 0, 100); // Limit message length
            
            // Send SMS using the Free SMS API
            $response = Http::get('https://free-sms-api.svxtract.workers.dev/', [
                'number' => $adminNumber,
                'message' => $smsMessage
            ]);

            if ($response->successful()) {
                Log::info('SMS notification sent successfully to admin', [
                    'admin_number' => $adminNumber,
                    'contact_name' => $fullName
                ]);
            } else {
                Log::warning('SMS notification failed', [
                    'response_status' => $response->status(),
                    'response_body' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('SMS notification error: ' . $e->getMessage(), [
                'contact_data' => $contactData
            ]);
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

        // Find user by email (decrypt stored email)
        $user = User::all()->first(function ($u) use ($credentials) {
            try {
                return Crypt::decryptString($u->email) === $credentials['email'];
            } catch (\Exception $e) {
                return false;
            }
        });

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        // Log in the user
        Auth::login($user, $request->filled('remember'));
        
        // Redirect based on user role
        if ($user->is_admin) {
            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, Admin!');
        }
        
        return redirect()->route('dashboard')->with('success', 'Login successful!');
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
