
@extends('layouts.app')

@section('title', 'Support Tickets')

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Support Tickets</h1>
        <a href="{{ route('tickets.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Create Ticket
        </a>
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('tickets.index') }}" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                            placeholder="Ticket ID, Subject, Customer..." value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label for="priority" class="form-label">Priority</label>
                        <select class="form-select" id="priority" name="priority">
                            <option value="">All Priorities</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                    
                    @if(!auth()->user()->hasRole('Customer'))
                    <div class="col-md-2 mb-3">
                        <label for="assigned" class="form-label">Assigned To</label>
                        <select class="form-select" id="assigned" name="assigned">
                            <option value="">All Agents</option>
                            <option value="me" {{ request('assigned') == 'me' ? 'selected' : '' }}>Assigned to Me</option>
                            <option value="unassigned" {{ request('assigned') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                            @foreach($customerServiceReps as $rep)
                                <option value="{{ $rep->id }}" {{ request('assigned') == $rep->id ? 'selected' : '' }}>
                                    {{ $rep->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    <div class="col-md-2 mb-3">
                        <label for="date_range" class="form-label">Date Range</label>
                        <select class="form-select" id="date_range" name="date_range">
                            <option value="">All Time</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="last_7_days" {{ request('date_range') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="last_30_days" {{ request('date_range') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                        </select>
                    </div>
                    
                    <div class="col-md-1 mb-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    
                    @if(request()->hasAny(['search', 'status', 'priority', 'assigned', 'date_range']))
                    <div class="col-auto mb-3">
                        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Clear
                        </a>
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="card shadow">
        <div class="card-body">
            @if($tickets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Subject</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Created</th>
                                <th>Assigned To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td>#{{ $ticket->id }}</td>
                                    <td>
                                        <a href="{{ route('tickets.show', $ticket) }}">
                                            {{ Str::limit($ticket->subject, 40) }}
                                        </a>
                                    </td>
                                    <td>
                                        @if(!auth()->user()->hasRole('Customer'))
                                            <a href="{{ route('customer-service.user-details', $ticket->user_id) }}">
                                                {{ $ticket->user->name }}
                                            </a>
                                        @else
                                            {{ $ticket->user->name }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $ticket->status_color }}">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $ticket->priority_color }}">
                                            {{ $ticket->priority }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>{{ $ticket->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $ticket->created_at->format('g:i A') }}</small>
                                    </td>
                                    <td>
                                        @if($ticket->assigned_to)
                                            {{ $ticket->assignedTo->name }}
                                        @else
                                            <span class="text-muted">Unassigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $tickets->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-ticket fa-4x mb-3 text-gray-300"></i>
                    <p class="text-gray-500 mb-0">No tickets found matching your criteria</p>
                    <a href="{{ route('tickets.create') }}" class="btn btn-primary mt-3">
                        Create New Ticket
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection