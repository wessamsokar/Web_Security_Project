
@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->id)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            Ticket #{{ $ticket->id }}
        </h1>
        <div>
            <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Tickets
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Ticket Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $ticket->subject }}</h6>
                    <span class="badge bg-{{ $ticket->status_color }} ms-2">{{ $ticket->status }}</span>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-dark">Description</h6>
                        <p class="mb-0">{{ $ticket->description }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="font-weight-bold text-dark">Conversation</h6>
                        
                        <div class="ticket-timeline">
                            @foreach($ticket->responses as $response)
                                @if(!$response->is_internal || !auth()->user()->hasRole('Customer'))
                                    <div class="ticket-response {{ $response->user_id === $ticket->user_id ? 'customer-response' : 'staff-response' }} mb-3">
                                        <div class="card {{ $response->user_id === $ticket->user_id ? 'border-primary' : 'border-success' }}">
                                            <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-2">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi {{ $response->user_id === $ticket->user_id ? 'bi-person' : 'bi-headset' }} me-2"></i>
                                                    <span class="font-weight-bold">{{ $response->user->name }}</span>
                                                    @if($response->is_internal)
                                                        <span class="badge bg-warning ms-2">Internal Note</span>
                                                    @endif
                                                </div>
                                                <small class="text-muted">{{ $response->created_at->format('M d, Y g:i A') }}</small>
                                            </div>
                                            <div class="card-body py-3">
                                                {{ $response->message }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        @if($ticket->status !== 'closed')
                            <form action="{{ route('tickets.reply', $ticket) }}" method="POST" class="mt-4">
                                @csrf
                                <div class="mb-3">
                                    <label for="message" class="form-label">Reply</label>
                                    <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                                </div>
                                
                                @if(!auth()->user()->hasRole('Customer'))
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_internal" name="is_internal" value="1">
                                        <label class="form-check-label" for="is_internal">
                                            Internal note (only visible to staff)
                                        </label>
                                    </div>
                                @endif
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-reply"></i> Send Reply
                                </button>
                            </form>
                        @else
                            <div class="alert alert-secondary mt-4">
                                <i class="bi bi-lock"></i> This ticket is closed. 
                                <form action="{{ route('tickets.reopen', $ticket) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-primary p-0 ms-2">Reopen ticket</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Ticket Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ticket Information</h6>
                </div>
                <div class="card-body">
                    <div class="ticket-info">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-dark">Status:</span>
                            <span class="badge bg-{{ $ticket->status_color }}">{{ $ticket->status }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-dark">Priority:</span>
                            <span class="badge bg-{{ $ticket->priority_color }}">{{ $ticket->priority }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-dark">Created:</span>
                            <span>{{ $ticket->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($ticket->order_id)
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-dark">Related Order:</span>
                                <a href="{{ route('orders.show', $ticket->order_id) }}">#{{ $ticket->order_id }}</a>
                            </div>
                        @endif
                    </div>

                    @if(!auth()->user()->hasRole('Customer'))
                        <hr>
                        <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="status" class="form-label">Update Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="priority" class="form-label">Update Priority</label>
                                <select class="form-select" id="priority" name="priority">
                                    <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="assigned_to" class="form-label">Assign To</label>
                                <select class="form-select" id="assigned_to" name="assigned_to">
                                    <option value="">Unassigned</option>
                                    @foreach($customerServiceReps as $rep)
                                        <option value="{{ $rep->id }}" {{ $ticket->assigned_to === $rep->id ? 'selected' : '' }}>
                                            {{ $rep->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label">Add Internal Note (Optional)</label>
                                <textarea class="form-control" id="note" name="note" rows="2"></textarea>
                                <small class="text-muted">This note will only be visible to staff</small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-save"></i> Update Ticket
                            </button>
                        </form>
                    @endif

                    @if($ticket->status !== 'closed')
                        <hr>
                        <form action="{{ route('tickets.close', $ticket) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-secondary w-100" 
                                    onclick="return confirm('Are you sure you want to close this ticket?')">
                                <i class="bi bi-x-circle"></i> Close Ticket
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-person-circle fa-2x me-3 text-gray-300"></i>
                        <div>
                            <div class="font-weight-bold">{{ $ticket->user->name }}</div>
                            <div class="small text-muted">{{ $ticket->user->email }}</div>
                        </div>
                    </div>
                    
                    @if(!auth()->user()->hasRole('Customer'))
                        <div class="mb-3">
                            <div class="text-dark mb-1">Customer ID: #{{ $ticket->user->id }}</div>
                            <div class="text-dark mb-1">Join Date: {{ $ticket->user->created_at->format('M d, Y') }}</div>
                            <div class="text-dark">Total Tickets: {{ $ticket->user->tickets->count() }}</div>
                        </div>
                        
                        <a href="{{ route('customer-service.user-details', $ticket->user_id) }}" class="btn btn-info btn-sm w-100">
                            <i class="bi bi-person-badge"></i> View Customer Profile
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .ticket-timeline {
        margin-top: 20px;
    }
    .customer-response {
        margin-right: 15%;
    }
    .staff-response {
        margin-left: 15%;
    }
</style>
@endsection