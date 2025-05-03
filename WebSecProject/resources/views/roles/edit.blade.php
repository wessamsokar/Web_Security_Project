@extends('layouts.master')

@section('title', ($role ? 'Edit' : 'Create') . ' Role')

@section('content')
<div class="container">
    <h1>{{ $role ? 'Edit' : 'Create' }} Role</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('roles_save', $role?->id) }}" method="POST">
        @csrf
        @method($role ? 'PUT' : 'POST')

        <div class="mb-3">
            <label for="name" class="form-label">Role Name</label>
            <input type="text" class="form-control" id="name" name="name" 
                   value="{{ old('name', $role?->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Permissions</label>
            <div class="row">
                @foreach($permissions as $permission)
                    <div class="col-md-4 mb-2">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" 
                                   name="permissions[]" value="{{ $permission->id }}"
                                   id="perm_{{ $permission->id }}"
                                   {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Save Role</button>
        <a href="{{ route('roles_list') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection