<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;



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
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->with('error', 'Invalid email or password. Please try again.')
            ->withInput($request->only('email'));
    }

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8|confirmed',
                'name' => 'required|string|max:255',
            ]);

            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'name' => $validated['name'],
            ]);
            // Assign default role
            $user->assignRole('Customer');

            Auth::login($user);

            return redirect()->route('register.complete');
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());

            // Check if error is due to duplicate email
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $errors = $e->errors();
                if (isset($errors['email']) && str_contains($errors['email'][0], 'taken')) {
                    return back()
                        ->withInput($request->except('password', 'password_confirmation'))
                        ->withErrors([
                            'email' => 'Email already exists'
                        ]);
                }
            }

            // For other errors
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors([
                    'email' => 'Registration failed'
                ]);
        }
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
            Log::error('Profile completion failed: ' . $e->getMessage());

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

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Try to find user by google_id first
            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                // If not found, try to find by email
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    // Update existing user with google_id and tokens
                    $user->google_id = $googleUser->getId();
                    $user->google_token = $googleUser->token;
                    $user->google_refresh_token = $googleUser->refreshToken;
                    $user->save();
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'google_token' => $googleUser->token,
                        'google_refresh_token' => $googleUser->refreshToken,
                        'password' => Hash::make(Str::random(16)),
                    ]);
                }
            } else {
                // Always update tokens for returning users
                $user->google_token = $googleUser->token;
                $user->google_refresh_token = $googleUser->refreshToken;
                $user->save();
            }

            Auth::login($user);
            if (!$user->hasRole('Customer')) {
                $user->assignRole('Customer');
            }
            return redirect('/');
        } catch (\Exception $e) {
            Log::error('Google login failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect('/')->with('error', 'Google login failed.' . $e->getMessage());
        }
    }

    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();

            $user = User::where('email', $githubUser->getEmail())->first();

            if ($user) {
                $user->github_id = $githubUser->getId();
                $user->github_token = $githubUser->token;
                $user->save();
            } else {
                $user = User::create([
                    'name' => $githubUser->getName() ?? $githubUser->getNickname(),
                    'email' => $githubUser->getEmail(),
                    'github_id' => $githubUser->getId(),
                    'github_token' => $githubUser->token,
                    'password' => Hash::make(Str::random(16)),
                ]);
            }

            Auth::login($user);

            if (!$user->hasRole('Customer')) {
                $user->assignRole('Customer');
            }

            return redirect('/');
        } catch (\Exception $e) {
            Log::error('GitHub login failed: ' . $e->getMessage());
            return redirect('/')->with('error', 'GitHub login failed.');
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
            return redirect('/')->with('error', 'Microsoft login failed.');
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

    public function facebookRedirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            $user = User::where('email', $facebookUser->getEmail())->first();

            if ($user) {
                $user->facebook_id = $facebookUser->getId();
                $user->facebook_token = $facebookUser->token;
                $user->save();
            } else {
                $user = User::create([
                    'name' => $facebookUser->getName() ?? $facebookUser->getNickname(),
                    'email' => $facebookUser->getEmail(),
                    'facebook_id' => $facebookUser->getId(),
                    'facebook_token' => $facebookUser->token,
                    'password' => Hash::make(Str::random(16)),
                ]);
            }

            Auth::login($user);

            if (!$user->hasRole('Customer')) {
                $user->assignRole('Customer');
            }

            return redirect('/');
        } catch (\Exception $e) {
            Log::error('Facebook login failed: ' . $e->getMessage());
            return redirect('/')->with('error', 'Facebook login failed.');
        }
    }
}
