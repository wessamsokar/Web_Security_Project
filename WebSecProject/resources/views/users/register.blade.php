@extends('layouts.master')
@section('title', 'Register Page')
@section('content')
<form action="{{ route('do_register') }}" method="post">
  {{ csrf_field() }}
  
  <div class="form-group">
    @if(request()->error)
      <div class="alert alert-danger">
        <strong> Error! </strong> {{ request()->error }}
      </div>
    @endif
  </div>

  <div class="form-group">
    @foreach($errors->all() as $error)
      <div class="alert alert-danger">
        <strong> Error!</strong> {{ $error }}
      </div>
    @endforeach
  </div>

  <div class="form-group mb-2">
    <label for="name" class="form-label">Name:</label>
    <input type="text" class="form-control" placeholder="Name" name="name" required>
  </div>

  <div class="form-group mb-2">
    <label for="email" class="form-label">Email:</label>
    <input type="email" class="form-control" placeholder="Email" name="email" required>
  </div>

  <div class="form-group mb-2">
    <label for="password" class="form-label">Password:</label>
    <input type="password" class="form-control" placeholder="Password" name="password" required>
  </div>

  <div class="form-group mb-2">
    <label for="password_confirmation" class="form-label">Password Confirmation:</label>
    <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required>
  </div>

  <div class="form-group mb-2">
    <label for="role" class="form-label">Role:</label>
    <select class="form-control" name="role" required>
      <option value="user">User</option>
      <option value="admin">Admin</option>
    </select>
  </div>

  <!-- Add Security Question Field -->
  <!-- <div class="form-group mb-2">
    <label for="security_question" class="form-label">Security Question:</label>
    <select class="form-control" name="security_question" required>
      <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
      <option value="What is the name of your first pet?">What is the name of your first pet?</option>
      <option value="What city were you born in?">What city were you born in?</option>
    </select>
  </div> -->

  <!-- Add Security Answer Field -->
  <!-- <div class="form-group mb-2">
    <label for="security_answer" class="form-label">Security Answer:</label>
    <input type="text" class="form-control" placeholder="Security Answer" name="security_answer" required>
  </div> -->

  <div class="form-group mb-2">
    <button type="submit" class="btn btn-primary">Register</button>
  </div>

</form>
@endsection