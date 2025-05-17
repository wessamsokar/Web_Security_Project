@extends('layouts.app')

@section('title', 'Customer Service Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Customer Service Dashboard</h1>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Open Tickets</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $openTickets }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-ticket-detailed fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Tickets</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingTickets }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-hourglass-split fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Resolved Tickets</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $resolvedTickets }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Urgent Tickets</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $urgentTickets }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Tickets -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Tickets</h6>
                    <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($recentTickets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Subject</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTickets as $ticket)
                                        <tr>
                                            <td>#{{ $ticket->id }}</td>
                                            <td>
                                                <a href="{{ route('tickets.show', $ticket) }}">
                                                    {{ Str::limit($ticket->subject, 40) }}
                                                </a>
                                            </td>
                                            <td>{{ $ticket->user->name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $ticket->status_color }}">
                                                    {{ $ticket->status }}
                                                </span>
                                            </td>
                                            <td>{{ $ticket->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-ticket fa-3x mb-3 text-gray-400"></i>
                            <p class="text-gray-500">No recent tickets found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('tickets.index', ['assigned' => 'me']) }}" class="btn btn-primary btn-block">
                            <i class="bi bi-person-check me-1"></i> My Assigned Tickets ({{ $myTickets }})
                        </a>
                    </div>
                    <div class="mb-3">
                        <a href="{{ route('tickets.index', ['priority' => 'urgent']) }}" class="btn btn-danger btn-block">
                            <i class="bi bi-exclamation-circle me-1"></i> View Urgent Tickets
                        </a>
                    </div>
                    <div class="mb-3">
                        <a href="{{ route('customer-service.user-search') }}" class="btn btn-info btn-block">
                            <i class="bi bi-search me-1"></i> Find Customer
                        </a>
                    </div>
                    <div class="mb-3">
                        <a href="{{ route('tickets.index', ['status' => 'open', 'assigned' => 'unassigned']) }}" class="btn btn-warning btn-block">
                            <i class="bi bi-list-check me-1"></i> Unassigned Tickets
                        </a>
                    </div>
                </div>
            </div>

            <!-- Customer Stats -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Overview</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-dark">Total Customers:</span>
                        <span class="font-weight-bold">{{ $customerCount }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-dark">Active Tickets:</span>
                        <span class="font-weight-bold">{{ $openTickets + $pendingTickets }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-dark">Closed Tickets:</span>
                        <span class="font-weight-bold">{{ $closedTickets }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection