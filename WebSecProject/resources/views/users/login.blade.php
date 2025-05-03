@extends('layouts.master')
@section('title', 'Login Page')
@section('content')
<form action="{{route('do_login')}}" method="post">
  {{ csrf_field() }}
    <div class="form-group">
        @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            <strong>Error!</strong> {{$error}}
        </div>
    @endforeach
  </div>

  <div class="form-group mb-2">
    <label for="model" class="form-label">Email:</label>
    <input type="email" class="form-control" placeholder="email" name="email" required>
  </div>

  <div class="form-group mb-2">
    <label for="model" class="form-label">Password:</label>
    <input type="password" class="form-control" placeholder="password" name="password" required>
  </div>

  <div class="form-group mb-2">
    <button type="submit" class="btn btn-primary">Login</button>
    <a href="{{route('login_with_google')}}" class="btn btn-danger">Login with Google</a>
  </div>

  <div class="form-group mb-2">
    <a href="{{ route('password.request') }}">Forgot Password?</a>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.querySelector('form');
        
        loginForm.addEventListener('submit', function(e) {
            // Disable the submit button to prevent multiple clicks
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = 'Logging in...';
            
            // Allow form submission to continue
            return true;
        });
    });
</script>
  
</form>

@endsection