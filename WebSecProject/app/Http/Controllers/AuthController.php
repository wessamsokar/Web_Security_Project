<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Block login if not verified
            if (is_null($user->email_verified_at)) {
                Auth::logout();
                return back()->withInput()->withErrors(['email' => 'Your email is not verified.']);
            }

            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->with('error', 'Please enter valid credentials or use a certificate.');
    }
    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Please enter a password.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'name' => null,
        ]);

        // Assign default role
        $user->assignRole('Customer');

        Auth::login($user);

        $title = "Verification Link";
        $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
        $link = route("verify", ['token' => $token]);
        Mail::to($user->email)->send(new VerificationEmail($link, $user->name));

        return redirect()->route('register.complete');

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $exists = User::where('email', $request->email)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function showCompleteForm()
    {
        if (!Auth::check()) {
            return redirect()->route('register');
        }
        return view('auth.register-step2');
    }

    public function completeRegistration(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('register');
        }

        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'birth_month' => 'required|string|size:2|in:01,02,03,04,05,06,07,08,09,10,11,12',
                'birth_year' => 'required|integer|min:1900|max:' . date('Y'),
                'birth_day' => 'required|integer|min:1|max:31',
            ]);

            // Validate the day based on month and year
            $maxDay = cal_days_in_month(CAL_GREGORIAN, intval($validated['birth_month']), intval($validated['birth_year']));
            if (intval($validated['birth_day']) > $maxDay) {
                return back()
                    ->withInput()
                    ->withErrors(['birth_day' => 'Invalid day for the selected month and year']);
            }

            $birthDate = date('Y-m-d', strtotime($validated['birth_year'] . '-' . $validated['birth_month'] . '-' . str_pad($validated['birth_day'], 2, '0', STR_PAD_LEFT)));

            $user = Auth::user();

            // First try to update without the name field to ensure other fields work
            $updateData = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'birth_date' => $birthDate,
            ];

            if (!$user->update($updateData)) {
                throw new \Exception('Failed to update user profile data');
            }

            // Now update the name field separately
            $user->name = $validated['first_name'] . ' ' . $validated['last_name'];
            if (!$user->save()) {
                throw new \Exception('Failed to update user name');
            }

            return redirect('/')->with('success', 'Profile completed successfully!');
        } catch (\Exception $e) {
            \Log::error('Profile completion failed: ' . $e->getMessage());

            // Return specific validation errors if they exist
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return back()->withErrors($e->errors())->withInput();
            }

            // Return a generic error for other exceptions
            return back()
                ->withInput()
                ->withErrors([
                    'first_name' => 'Failed to update profile. Please check your input and try again.',
                    'error' => $e->getMessage()
                ]);
        }
    }


    public function verify(Request $request)
    {

        $decryptedData = json_decode(Crypt::decryptString($request->token), true);
        $user = User::find($decryptedData['id']);
        if (!$user)
            abort(401);
        $user->email_verified_at = Carbon::now();
        $user->save();
        return view('users.verified', compact('user'));
    }

    public function forgotPassword()
    {
        return view('auth.forgot_password');
    }

    public function sendTemporaryPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();

        $tempPassword = Str::random(10);
        $user->password = bcrypt($tempPassword);
        $user->temp_password = true;
        $user->temp_password_expires_at = now()->addMinutes(30);

        $user->save();

        Mail::raw("Your temporary password is: {$tempPassword}", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Temporary Password')
                ->from('mohamed102khaled@gmail.com', 'websec');
        });

        return redirect()->route('login')->with('success', 'Temporary password sent to your email.');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => now(),
                    'password' => bcrypt(Str::random(16)),
                ]
            );

            // Assign Customer role if not already assigned
            if (!$user->hasRole('Customer')) {
                $user->assignRole('Customer');
            }

            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'Successfully logged in with Google!');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }


    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();

            $user = User::firstOrCreate(
                ['facebook_id' => $facebookUser->getId()],
                [
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
                    'password' => bcrypt(Str::random(16)),
                    'facebook_token' => $facebookUser->token,
                    'facebook_refresh_token' => $facebookUser->refreshToken ?? null,
                    'email_verified_at' => now()
                ]
            );

            // Assign customer role if not already assigned
            if (!$user->hasRole('customer')) {
                $user->assignRole('customer');
            }

            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Successfully logged in with Facebook!');

        } catch (\Exception $e) {
            \Log::error('Facebook login error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect('/login')->with('error', 'Facebook login failed: ' . $e->getMessage());
        }
    }


    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->stateless()->user();

            $user = User::where('email', $githubUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $githubUser->getName() ?? $githubUser->getNickname(),
                    'email' => $githubUser->getEmail(),
                    'email_verified_at' => now(),
                    'password' => bcrypt(uniqid()),
                ]);
                $user->assignRole('Customer');
            }

            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Successfully logged in with GitHub!');
        } catch (\Exception $e) {
            return redirect('/login')->withErrors('Unable to login using GitHub.');
        }
    }


    public function redirectToMicrosoft()
    {
        return Socialite::driver('microsoft')->redirect();
    }

    public function handleMicrosoftCallback()
    {
        try {
            $microsoftUser = Socialite::driver('microsoft')->user();

            $user = User::where('email', $microsoftUser->getEmail())->first();

            if ($user) {
                $user->microsoft_id = $microsoftUser->getId();
                $user->microsoft_token = $microsoftUser->token;
                $user->save();
            } else {
                $user = User::create([
                    'name' => $microsoftUser->getName() ?? $microsoftUser->getNickname(),
                    'email' => $microsoftUser->getEmail(),
                    'microsoft_id' => $microsoftUser->getId(),
                    'microsoft_token' => $microsoftUser->token,
                    'password' => Hash::make(Str::random(16)),
                ]);
            }

            Auth::login($user);

            if (!$user->hasRole('Customer')) {
                $user->assignRole('Customer');
            }

            return redirect('/');
        } catch (\Exception $e) {
            Log::error('Microsoft login failed: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Microsoft login failed.');
        }
    }

    public function redirectToDiscord()
    {
        return Socialite::driver('discord')->scopes(['identify', 'email'])->redirect();
    }


    public function handleDiscordCallback()
    {
        try {
            $discordUser = Socialite::driver('discord')->user();

            // Discord may not return email if not verified
            if (!$discordUser->getEmail()) {
                return redirect('/')->with('error', 'Discord account does not have a verified email.');
            }

            $user = User::where('email', $discordUser->getEmail())->first();

            if ($user) {
                $user->discord_id = $discordUser->getId();
                $user->discord_token = $discordUser->token;
                $user->save();
            } else {
                $user = User::create([
                    'name' => $discordUser->getName() ?? $discordUser->getNickname(),
                    'email' => $discordUser->getEmail(),
                    'discord_id' => $discordUser->getId(),
                    'discord_token' => $discordUser->token,
                    'password' => Hash::make(\Str::random(16)),
                ]);
            }

            Auth::login($user);

            if (!$user->hasRole('Customer')) {
                $user->assignRole('Customer');
            }

            return redirect('/');
        } catch (\Exception $e) {
            Log::error('Discord login failed: ' . $e->getMessage());
            return redirect('/')->with('error', 'Discord login failed.');
        }
    }

    public function loginWithCertificate(Request $request)
    {
        // dd($_SERVER);

        $clientCert = $_SERVER['SSL_CLIENT_S_DN'] ??
            $_SERVER['REDIRECT_SSL_CLIENT_S_DN'] ??
            null;

        if (!$clientCert) {
            return back()->withErrors([
                'certificate' => 'No valid certificate found. Please ensure you have a valid client certificate installed.'
            ]);
        }

        // Extract email address more robustly from certificate DN string
        preg_match('/emailAddress=([^,\/]+)/i', $clientCert, $matches);
        $email = $matches[1] ?? null;

        if (!$email) {
            return back()->withErrors([
                'certificate' => 'Valid certificate found, but no email address in subject.'
            ]);
        }

        // Find user by email from cert
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Certificate email not recognized. Please contact support.'
            ]);
        }

        // Login the user and redirect to intended page or home
        Auth::login($user);
        return redirect()->intended('dashboard')->with('success', 'Logged in successfully using certificate authentication.');
    }

}
