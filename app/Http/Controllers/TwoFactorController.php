<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use PragmaRX\Google2FALaravel\Google2FA;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;

class TwoFactorController extends Controller
{
    public function setup()
    {
        $user = User::find(Auth::id());
        $google2fa = app('pragmarx.google2fa');

        // Generate secret key if not already set
        if (!$user->google2fa_secret) {
            $user->google2fa_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        $secretKey = $user->google2fa_secret;

        // Generate QR Code using BaconQrCode
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new ImagickImageBackEnd()
        );
        $writer = new Writer($renderer);

        $qrCodeUrl = 'data:image/png;base64,' . base64_encode(
            $writer->writeString(
                $google2fa->getQRCodeUrl(
                    config('app.name'), // Application name
                    $user->email,       // User's email
                    $secretKey          // User's 2FA secret key
                )
            )
        );

        // List of countries (you can replace this with a database query or API call)
        $countries = [
            'US' => 'United States',
            'CA' => 'Canada',
            'GB' => 'United Kingdom',
            'PH' => 'Philippines',
            'IN' => 'India',
        ];

        return view('auth.2fa.setup.setup', compact('qrCodeUrl', 'secretKey', 'countries'));
    }
public function verify(Request $request)
{
    // Validate the common fields
    $request->validate([
        'one_time_password' => 'required|digits:6',
    ], [
        'one_time_password.required' => 'The OTP is required.',
        'one_time_password.digits' => 'The OTP must be 6 digits.',
    ]);

    $google2fa = app('pragmarx.google2fa');
    $user = User::find(Auth::id());

    // Check if the request is for setup verification
    if ($request->has('setup')) {
        $request->validate([
            'country' => 'required|string|max:255',
        ], [
            'country.required' => 'Please select a country.',
        ]);

        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->one_time_password);

        if ($valid) {
            $user->two_factor_enabled = true;
            $user->country = $request->country; // Save the selected country
            $user->save();

            return redirect()->route('profile.settings')->with('success', 'Two-Factor Authentication setup verified successfully.');
        } else {
            return back()->withErrors(['one_time_password' => 'Invalid OTP for setup verification.']);
        }
    }

    // Check if the request is for login verification
    if ($request->has('login')) {
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->one_time_password);

        if ($valid) {
            // Mark the user as verified for this session
            $request->session()->put('2fa_verified', true);

            return redirect()->intended('/')->with('success', 'Two-Factor Authentication login verified successfully.');
        } else {
            return back()->withErrors(['one_time_password' => 'Invalid OTP for login verification.']);
        }
    }

    // Default response if neither setup nor login is specified
    return back()->withErrors(['error' => 'Invalid request type.']);
}
    public function disable(Request $request)
    {
        $request->validate([
        'password' => 'required',
    ]);
        $user = User::find(Auth::id());
        
    // Verify the user's password
        if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors(['password' => 'Incorrect password.']);
        }

    // Disable 2FA
        $user->google2fa_secret = null;
        $user->two_factor_enabled = false;
        $user->save();

        return redirect()->route('profile.settings')->with('success', 'Two-Factor Authentication disabled successfully.');
    }

    // Show the 2FA verification form
    public function showVerifyForm()
    {
        return view('auth.2fa.login.verify');
    }

    // Handle 2FA OTP verification during login
    public function verifyLogin(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|digits:6',
        ]);

        $user = Auth::user();
        $google2fa = app('pragmarx.google2fa');

        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->one_time_password);

        if ($valid) {
            // Mark the user as verified for this session
            $request->session()->put('2fa_verified', true);

            return redirect()->intended('/'); // Redirect to the intended page
        } else {
            return back()->withErrors(['one_time_password' => 'Invalid code.']);
        }
    }

    public function sendGmailOtp(Request $request)
    {
        $user = Auth::user();

        // Generate a random 6-digit OTP
        $otp = rand(100000, 999999);

        // Save the OTP in the session or database (optional)
        $request->session()->put('gmail_otp', $otp);

        // Send the OTP to the user's email
        Mail::to($user->email)->send(new SendOtpMail($otp));

        return redirect()->route('gmail.verify')->with('success', 'OTP has been sent to your email.');
    }
    public function verifyGmailOtp(Request $request)
    {
        $request->validate([
            'gmail_otp' => 'required|digits:6',
        ]);

        $userOtp = $request->session()->get('gmail_otp'); // Retrieve OTP from session

        if ($request->gmail_otp == $userOtp) {
            // Mark the user as verified for this session
            $request->session()->forget('gmail_otp'); // Clear the OTP from session
            return redirect()->intended('/')->with('success', 'Gmail OTP verified successfully.');
        } else {
            return back()->withErrors(['gmail_otp' => 'Invalid Gmail OTP.']);
        }
    }
        // Show the Gmail OTP verification form
        public function showGmailVerifyForm()
        {
            return view('auth.gmail_otp.verify');
        }
}