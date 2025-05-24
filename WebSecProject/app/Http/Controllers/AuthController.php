<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            ]);

            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'name' => null,

            ]);
            $user->assignRole('Customer');

            Auth::login($user);

            return redirect()->route('register.complete');
        } catch (\Exception $e) {
            \Log::error('Registration failed: ' . $e->getMessage());

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
            return redirect('/login')->with('error', 'GitHub login failed.');
        }
    }
<<<<<<< Updated upstream
=======


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

    public function loginWithCertificate(Request $request)
    {
        // Extract certificate subject from the server environment
        $clientCert = $_SERVER['SSL_CLIENT_S_DN'] ?? null;

        if ($clientCert) {
            // Example: parse email from DN string like: "emailAddress=user@example.com,CN=User Name,O=Example Org"
            preg_match('/emailAddress=([^,]+)/', $clientCert, $matches);
            $email = $matches[1] ?? null;

            if ($email) {
                $user = User::where('email', $email)->first();
                if ($user) {
                    Auth::login($user);
                    return redirect()->intended('/');
                } else {
                    return back()->withErrors(['email' => 'Certificate email not recognized.']);
                }
            }
        }

        return back()->withErrors(['certificate' => 'No valid certificate found.']);
    }
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = \Socialite::driver('google')->user();

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
                        // You may want to set a random password or leave it null if allowed
                        'password' => \Hash::make(Str::random(16)),
                    ]);
                }
            }

            Auth::login($user);
            // Assign role only if user is new or doesn't have it
            if (!$user->hasRole('Customer')) {
                $user->assignRole('Customer');
            }
            return redirect('/');
        } catch (\Exception $e) {
            Log::error('Google login failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect('/login')->with('error', 'Google login failed.' . $e->getMessage());
        }
        public function redirectToDiscord()
    {
        return Socialite::driver('discord')->scopes(['identify', 'email'])->redirect();
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
            return redirect('/login')->with('error', 'Facebook login failed.');
        }
    }

}
>>>>>>> Stashed changes
}
