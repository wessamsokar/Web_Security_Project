@extends('layouts.master')

@section('title', ($permission->id ? 'Edit' : 'Create') . ' Permission')

@section('content')
<div class="container">
    <h1>{{ $permission->id ? 'Edit' : 'Create' }} Permission</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('permissions_save', $permission->id) }}" method="POST">
                @csrf
                @method($permission->id ? 'PUT' : 'POST')

                <div class="mb-3">
                    <label for="name" class="form-label">Permission Name</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ old('name', $permission->name) }}" required>
                    <div class="form-text">
                        This is the internal name used by the system (e.g., "edit_users").
                    </div>
                </div>

                <div class="mb-3">
                    <label for="display_name" class="form-label">Display Name</label>
                    <input type="text" class="form-control" id="display_name" name="display_name" 
                           value="{{ old('display_name', $permission->display_name) }}">
                    <div class="form-text">
                        A more human-readable name (e.g., "Edit Users").
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Save Permission</button>
                    <a href="{{ route('permissions_list') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection