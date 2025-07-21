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

        return view('auth.2fa.setup', compact('qrCodeUrl', 'secretKey', 'countries'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|digits:6',
            'country' => 'required|string|max:255',
 
        ]);

        $user = User::find(Auth::id());
        $google2fa = app('pragmarx.google2fa');

        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->one_time_password);

        if ($valid) {
            $user->two_factor_enabled = true;
             $user->country = $request->country; // Save the selected country

            $user->save();
            return redirect()->route('profile.settings')->with('success', 'Two-Factor Authentication verified successfully.');
        } else {
            return back()->withErrors(['one_time_password' => 'Invalid code.']);
        }
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
        return view('auth.2fa.verify');
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
}