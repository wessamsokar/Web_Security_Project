<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use DB;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;
use Illuminate\Cache\RateLimiter as CacheRateLimiter;
use Illuminate\Support\Facades\RateLimiter;

class UsersController extends Controller
{
    use ValidatesRequests;
    
    
public function list(Request $request)
{
    // Authorization check
    if (!auth()->user()->hasPermissionTo('show_users')) {
        abort(403);
    }

    // Start the query
    $query = User::with('roles');

    // Filter by name
    if ($request->has('name')) {
        $query->where('name', 'like', '%' . $request->input('name') . '%');
    }

    // Filter by email
    if ($request->has('email')) {
        $query->where('email', 'like', '%' . $request->input('email') . '%');
    }

    // Filter by role
    if ($request->filled('role')) {
        $query->whereHas('roles', function($q) use ($request) {
            $q->where('name', $request->input('role'));
        });
    }

    // Get users
    $users = $query->get();

    // Pass the filters to the view
    $filters = $request->only(['name', 'email', 'role']);

    return view("users.list", compact('users', 'filters'));
}

public function edit(Request $request, User $user = null) 
{
    $user = $user??auth()->user();

    if(auth()->id()!=$user?->id) {
        if(!auth()->user()->hasPermissionTo('edit_users')) abort(403);
    }

    // Add this line to get all users
    $allUsers = User::all();

    $roles = [];
    foreach(Role::all() as $role) {
        $role->taken = ($user->hasRole($role->name));
        $roles[] = $role;
    }

    $permissions = [];
    $directPermissionsIds = $user->permissions()->pluck('id')->toArray();
    foreach(Permission::all() as $permission) {
        $permission->taken = in_array($permission->id, $directPermissionsIds);
        $permissions[] = $permission;
    } 
    
    return view('users.edit', compact('user', 'roles', 'permissions', 'allUsers'));
}

public function save(Request $request, User $user = null) 
{
    try 
    {
        // Authorization check
        if ($user && auth()->id() != $user->id) {
            if (!auth()->user()->hasPermissionTo('edit_users')) {
                abort(403);
            }
        }

        // Validation (updated rules)
        $validated = $request->validate([
        'email' => ['required', 'string', 'max:255' , Rule::unique('users')->ignore($user->id)],
        'name' => ['required', 'string', 'max:128'],
        'password' => [$user ? 'nullable' : 'required', 'string', 'max:256'],
        'roles' => ['required', 'array'],
        'roles.*' => ['string', 'exists:roles,name'], // More flexible validation
        'permissions' => ['array'],
        'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        // Create/update user
        $user = $user ?? new User();
        $user->fill($request->only('name', 'email'));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        // Sync roles/permissions (if authorized)
        if (auth()->user()->hasPermissionTo('edit_users')) {
            $user->syncRoles($request->input('roles', []));
            $user->syncPermissions($request->input('permissions', []));

            // Clear cached permissions for THIS USER only
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        }

        return redirect(route('profile', ['user' => $user->id]))->with('success', 'User saved successfully!');
    
    }

    catch (\Exception $e) {
            return back()->withInput()
            ->with('error', 'Error updating user: ' . $e->getMessage());
        }
}

public function delete(Request $request, User $user) 
{
    // Only admin can delete users
    // if (Auth::user()->role === 'admin') {
    //     $user->delete();
    //     return redirect()->route('users_list');
    // }

    if(auth()->user()->hasPermissionTo('delete_users')) {
        $user->delete();
        return redirect()->route('users_list');
    }
    else {
        abort(403);
    }

    return redirect()->route('home')->with('error', 'You do not have permission to delete this user.');
}


public function register(Request $request) 
{
        return view('users.register');
}


public function doRegister(Request $request)
{
    try{
    // Validate the request
    $this->validate($request, [
        'name' => ['required', 'string', 'min:5'],
        'email' => ['required', 'email', 'unique:users'],
        'password' => ['required', 'confirmed', Password::min(5)->numbers()->letters()->mixedCase()->symbols()],
        'role' => ['required', 'in:user,admin'],
        // 'security_question' => ['required', 'string'],
        // 'security_answer' => ['required', 'string'],
    ]);}

    catch(\Exception $e) {
        return redirect()->back()->withInput($request->input())->withErrors('Invalid registration information.');
    }       

    // Create the user
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    
    $user->security_question = $request->security_question;
    $user->security_answer = bcrypt($request->security_answer); // Hash the security answer
    $user->verification_code = Str::random(6); // Generate a 6-digit code
    $user->verification_code_expires_at = now()->addMinutes(30); // Code expires in 30 minutes
    $user->verification_token = Str::random(60); // Generate a verification token
    $user->save();

    // This is the proper way to assign a role using Spatie
    $user->assignRole($request->role);

    // Log the user in
    Auth::login($user);

    // Send the verification code via email
    Mail::to($user->email)->send(new VerificationCodeMail($user->verification_code));

    // Redirect to the verification code form
    return redirect()->route('verification.code.form')->with('success', 'Registration successful! Please check your email for the verification code.');
}

public function login(Request $request) 
{
        return view('users.login');
}


public function doLogin(Request $request) 
{

    

    
    if (!Auth::attempt(['email'=> $request->email, 'password'=> $request->password])) {
        
        return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');
    }
    
    $user = Auth::user();
    
    
    // $user = User::where('email', $request->email)->first();
    // Auth::setUser($user);

    // if(!$user->email_verified_at){
        
    //     Auth::logout();
    //     return redirect()->back()->withInput($request->input())->withErrors('Your email is not verified.');
    // }
    
    
    return redirect('/')->with('success', 'Login Successful!');
}



public function doLogout(Request $request) 
{
        Auth::logout();
        return redirect('/');
}


public function profile(Request $request, User $user = null) 
{

        $user = $user??auth()->user();

        if (auth()->id() != $user->id && !auth()->user()->hasPermissionTo('show_users')) {
            abort(401, 'Unauthorized');
        }

        $permissions = [];

        foreach($user->permissions as $permission) {
            $permissions[] = $permission;
        }

        foreach($user->roles as $role) {
            foreach($role->permissions as $permission) {
                $permissions[] = $permission;
            }
        }
        return view('users.profile', compact('user' , 'permissions'));
}
    

public function updatePassword(Request $request)
{
             // Validate the request
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'confirmed', Password::min(5)->numbers()->letters()->mixedCase()->symbols()],
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Check if the current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update the password
        $user->update([
            'password' => Hash::make($request->new_password) // Hash the new password
        ]);     

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Password updated successfully!');
    
}


public function verifyCode(Request $request)
{
    // Ensure the user is authenticated
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'You must be logged in to verify your email.');
    }

    // Validate the request
    $request->validate([
        'code' => 'required|string|size:6',
    ]);

    // Get the authenticated user
    $user = Auth::user();

    // Check if the code matches and is not expired
    if ($user->verification_code === $request->code && $user->verification_code_expires_at > now()) {
        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->verificat1ion_code_expires_at = null;
        $user->save();

        return redirect()->route('home')->with('success', 'Email verified successfully!');
    }

    return back()->withErrors(['code' => 'Invalid or expired verification code.']);
}


// Verify email using link
public function verifyLink($token)
{
     // Find the user by verification token
     $user = User::where('verification_token', $token)->first();

     if ($user && !$user->email_verified_at) {
         $user->email_verified_at = now();
         $user->verification_token = null;
         $user->save();

         return redirect()->route('home')->with('success', 'Email verified successfully!');
     }

     return redirect()->route('home')->with('error', 'Invalid or expired verification link.');
}



    public function resendCode(Request $request)
{
    // Ensure the user is authenticated
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'You must be logged in to resend the verification code.');
    }

    // Get the authenticated user
    $user = Auth::user();

    // Generate a new code
    $user->verification_code = Str::random(6);
    $user->verification_code_expires_at = now()->addMinutes(30);
    $user->save();

    // Send the new code via email
    Mail::to($user->email)->send(new VerificationCodeMail($user->verification_code));

    return back()->with('success', 'A new verification code has been sent to your email.');
}

public function redirectToGoogle()
{

    return \Socialite::driver('google')->redirect();
}

public function handleGoogleCallback() {
    try {
        $googleUser = \Socialite::driver('google')->user();
        // $user = User::where('email', $googleUser->getEmail())->first();

        
            // If the user doesn't exist, create a new one
            $user = User::updateOrCreate([
                'google_id' => $googleUser->id(),
            ], [
                'name' => $googleUser->name(),
                'email' => $googleUser->email(),
                'google_token'=> $googleUser->token(),
                'google_refresh_token'=> $googleUser->refreshToken(),

            ]);
        

        Auth::login($user);

        return redirect('/')->with('success', 'Login Successful!');

    } catch (\Exception $e) {
        return redirect('/')->with('error', 'Failed to login with Google: ' . $e->getMessage());
    }
   
    }

}