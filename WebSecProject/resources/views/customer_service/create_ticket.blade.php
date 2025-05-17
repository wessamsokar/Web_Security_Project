@extends('layouts.app')

@section('title', 'Create Ticket for Customer')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Ticket for {{ $user->name }}</h1>
        <a href="{{ route('customer-service.user-details', $user) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Customer
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ticket Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('tickets.store') }}" method="POST">
                        @csrf
                        
                        <!-- Hidden field for user ID -->
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                id="subject" name="subject" value="{{ old('subject') }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="order_id" class="form-label">Related Order (Optional)</label>
                                <select class="form-select @error('order_id') is-invalid @enderror" 
                                    id="order_id" name="order_id">
                                    <option value="">No related order</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                            #ORD-{{ $order->id }} ({{ $order->created_at->format('M d, Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('order_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-select @error('priority') is-invalid @enderror" 
                                    id="priority" name="priority" required>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Create Ticket
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Customer Information Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-person-circle fa-2x me-3 text-gray-300"></i>
                        <div>
                            <div class="font-weight-bold">{{ $user->name }}</div>
                            <div class="small text-muted">{{ $user->email }}</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="text-dark mb-1">Customer ID: #{{ $user->id }}</div>
                        <div class="text-dark mb-1">Join Date: {{ $user->created_at->format('M d, Y') }}</div>
                    </div>
                    
                    <a href="{{ route('customer-service.user-details', $user) }}" class="btn btn-info btn-sm w-100">
                        <i class="bi bi-person-badge"></i> View Full Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection