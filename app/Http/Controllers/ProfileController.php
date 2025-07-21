<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function settings()
{
    $countries = [
         'US' => 'United States',
    'CA' => 'Canada',
    'GB' => 'United Kingdom',
    'AU' => 'Australia',
    'IN' => 'India',
    'PH' => 'Philippines',
    'DE' => 'Germany',
    'FR' => 'France',
    'JP' => 'Japan',
    'KR' => 'South Korea',
    'CN' => 'China',
    'BR' => 'Brazil',
    'MX' => 'Mexico',
    'ZA' => 'South Africa',
    'IT' => 'Italy',
    'ES' => 'Spain',
    'RU' => 'Russia',
    'NG' => 'Nigeria',
    'ID' => 'Indonesia',
    'TH' => 'Thailand',
    'VN' => 'Vietnam',
    'MY' => 'Malaysia',
    'SG' => 'Singapore',
    'AE' => 'United Arab Emirates',
    'SA' => 'Saudi Arabia',
    'AR' => 'Argentina',
    'TR' => 'Turkey',
    'NL' => 'Netherlands',
    'SE' => 'Sweden',
    'NO' => 'Norway',
        // Add more countries as needed
    ];

    $timezones = timezone_identifiers_list(); // Get the list of timezones

    return view('profile.account_settings', compact('countries', 'timezones'));
}
   public function update(Request $request)
{
    $user = User::find(Auth::id());

    if (!$user) {
        return redirect()->route('profile.settings')->with('error', 'User not found.');
    }

    // Check what is being updated
    if ($request->has('display_mode')) {
        $request->validate([
            'display_mode' => 'in:light,dark',
        ]);
        $user->display_mode = $request->display_mode;
        $user->save();

        return redirect()->route('profile.settings')->with('success', 'Display mode Updated successfully.');
    }

    if ($request->has('country')) {
        $request->validate([
            'country' => 'nullable|string|max:255',
        ]);

        $user->country = $request->country;
        $user->save();

        return redirect()->route('profile.settings')->with('success', 'Country Updated successfully!.');
    }
        if ($request->has('timezone')) {
        $request->validate([
            'timezone' => 'nullable|string|max:255',
        ]);

        $user->timezone = $request->timezone;
        $user->save();

        return redirect()->route('profile.settings')->with('success', 'Timezone Updated successfully!.');
    }
    // Language update
    if ($request->has('language')) {
        $request->validate([
            'language' => 'nullable|string|max:255',
        ]);

        $user->language = $request->language;
        $user->save();

        return redirect()->route('profile.settings')->with('success', 'Language Updated successfully!.');
    }
    // Firstname and lastname update
    if ($request->has('firstname') || $request->has('lastname')) {
        $request->validate([
            'firstname' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
        ]);

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->save();

        return redirect()->route('profile.settings')->with('success', 'Firstname and lastname updated successfully.');
    }

    // Email update
    if ($request->has('email')) {
    $request->validate([
        'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
    ]);
    $user->email = $request->email;
    $user->save();
    return redirect()->route('profile.settings')->with('success', 'Email updated successfully.');
    }
    // Password update
    if ($request->has('password')) {
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                function ($attribute, $value, $fail) {
                    if (strlen($value) > 72) {
                        $fail('The password is too long. It must be 72 characters or fewer.');
                    }
                },
            ],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.settings')->with('success', 'Password updated successfully.');
    }
}
}