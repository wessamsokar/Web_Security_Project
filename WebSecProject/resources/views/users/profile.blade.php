@extends('layouts.master')
@section('title', 'User Profile')
@section('content')
<div class="container">
    <h1>Profile Content</h1>

    <div class="form-group">
       @foreach($errors->all() as $error)
        <div class="alert alert-danger">
          <strong> Error!</strong>{{$error}}
        </div>
       @endforeach
    </div>

    <!-- Display user information -->

    <!-- <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">User Information</h5>
            <p class="card-text"><strong>Name:</strong> {{ $user->name }}</p>
            <p class="card-text"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="card-text"><strong>Role:</strong> <span class="badge bg-primary">{{$user->role}}</span> </p>
            <p class="card-text"><strong>Approved permissions:</strong> <span class="badge bg-primary">{{$user->permission}}</span> </p>
        </div>
    </div> -->

    <div class="d-flex justify-content-center">

    <div class="m-1 col-sm-10 col-md-8 col-lg-10">

        <table class="table table-striped">
            <tr>
                <th>Name</th><td>{{$user->name}}</td>
            </tr>

            <tr>
                <th>Email</th><td>{{$user->email}}</td>
            </tr>

            <tr>
                <th>Roles</th>
                
                <td>
                    @foreach($user->roles as $role)
                        <span class="badge bg-primary">{{$role->name}}</span>
                    @endforeach
                </td>
            </tr>

            <tr>
                <th>Direct Permissions</th>
                    <td>
                        @foreach($permissions as $permission)
                            <span class="badge bg-success">{{$permission->display_name}}</span>
                        @endforeach
                    </td>
            </tr>
        </table>
      </div>
    </div>

    <div class="row">
        <div class="col col-10">
        </div>
            <div class="col col-2">
                @can('edit_users')
                    <a href="{{route('users_edit')}}" class="btn btn-success form-control">Edit</a>
                @endcan
            </div>
    </div>
    <!-- Change password form -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Change Password</h5>

            <form action="{{ route('profile.update_password') }}" method="post">
            @csrf
            <div class="form-group mb-2">
                <label for="current_password">Current Password:</label>
                <input type="password" class="form-control" name="current_password" required>
            </div>
            <div class="form-group mb-2">
                <label for="new_password">New Password:</label>
                <input type="password" class="form-control" name="new_password" required>
            </div>
            <div class="form-group mb-2">
                <label for="new_password_confirmation">Confirm New Password:</label>
                <input type="password" class="form-control" name="new_password_confirmation" required>
            </div>
                    <button type="submit" class="btn btn-primary">Update Password</button>
            </form>

        </div>
    </div>
</div>
@endsection