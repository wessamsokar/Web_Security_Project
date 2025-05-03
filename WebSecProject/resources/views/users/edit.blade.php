@extends('layouts.master')
@section('title', 'Edit User')
@section('content')

<div class="container">

    <h1>{{ $user->id ? 'Edit' : 'Add' }} User</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

     <!-- Debugging: Check authenticated user's permissions
     <pre>Authenticated User Permissions: {{ print_r(auth()->user()->getAllPermissions()->pluck('name'), true) }}</pre> -->

    <form action="{{ route('users_save', $user->id) }}" method="post">
        @csrf
        @method('PUT')
        @can('edit_users')

            <div class="mb-3">
                <label for="user_select" class="form-label">Select User</label>
                <select class="form-select" name="user_select" id="user_select" onchange="updateUserDetails(this.value)">
                    <option value="">Choose a user...</option>
                    @foreach($allUsers as $u)
                        <option value="{{ $u->id }}" 
                                data-name="{{ $u->name }}"
                                data-email="{{ $u->email }}"
                                {{ $user->id == $u->id ? 'selected' : '' }}>
                            {{ $u->name }} ({{ $u->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password (leave empty to keep current)</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       name="password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm new password">
            </div>



            <div class="col-12 mb-2">
                <label for="model" class="form-label">Roles:</label>

                <select multiple class="form-select" name="roles[]">
                    @foreach($roles as $role)
                        <option value='{{$role->name}}' {{$role->taken?'selected':''}}> {{$role->name}} </option>
                    @endforeach
                </select>

            </div>
          
            <div class="col-12 mb-2">
                <label for="model" class="form-label">Direct Permissions:</label>

                <select multiple class="form-select" name="permissions[]">
                    @foreach($permissions as $permission)
                        <option value='{{$permission->name}}' {{$permission->taken?'selected':''}}> {{$permission->display_name}} </option>
                    @endforeach
                </select>

            </div>

        @endcan

        <button type="submit" class="btn btn-primary">Save</button>
        
    </form>

</div>

<script>
function updateUserDetails(userId) {
    if (!userId) return;
    const option = document.querySelector(`#user_select option[value="${userId}"]`);
    if (option) {
        document.getElementById('name').value = option.dataset.name;
        document.getElementById('email').value = option.dataset.email;
        // Redirect to the edit page for the selected user
        window.location.href = '{{ url("users/edit") }}/' + userId;
    }
}
</script>

@endsection