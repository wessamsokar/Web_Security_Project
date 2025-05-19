@extends('layouts.app')

@section('content')
    <h1>Forgot Password</h1>
    <form method="POST" action="{{ route('send_temp_password') }}">
        @csrf
        <input type="email" name="email" required>
        <button type="submit">Send Temporary Password</button>
    </form>
@endsection