<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketResponse;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'assignedTo']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                  ->orWhere('subject', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Assignment filter
        if ($request->filled('assigned')) {
            if ($request->assigned === 'me') {
                $query->where('assigned_to', Auth::id());
            } elseif ($request->assigned === 'unassigned') {
                $query->whereNull('assigned_to');
            } elseif (is_numeric($request->assigned)) {
                $query->where('assigned_to', $request->assigned);
            }
        }

        // Date range filter
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'last_7_days':
                    $query->whereDate('created_at', '>=', now()->subDays(7));
                    break;
                case 'last_30_days':
                    $query->whereDate('created_at', '>=', now()->subDays(30));
                    break;
                case 'this_month':
                    $query->whereYear('created_at', now()->year)
                          ->whereMonth('created_at', now()->month);
                    break;
            }
        }

        // If user is a customer, only show their tickets
        if (Auth::user()->hasRole('Customer')) {
            $query->where('user_id', Auth::id());
        }

        $tickets = $query->latest()->paginate(10);
        $customerServiceReps = User::role('Customer Service')->get();

        return view('tickets.index', compact('tickets', 'customerServiceReps'));
    }

    public function create()
    {
        $user = Auth::user();
        $orders = $user->hasRole('Customer') 
            ? Order::where('user_id', $user->id)->latest()->get() 
            : [];

        return view('tickets.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'order_id' => 'nullable|exists:orders,id',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        // Set user ID to current authenticated user
        $validated['user_id'] = Auth::id();
        
        // For customer service reps creating tickets on behalf of customers
        if (!Auth::user()->hasRole('Customer') && $request->filled('user_id')) {
            $request->validate([
                'user_id' => 'exists:users,id'
            ]);
            $validated['user_id'] = $request->user_id;
        }

        $ticket = Ticket::create($validated);

        // Auto-assign to CS rep if setting enabled
        $autoAssign = config('customer_service.auto_assign', false);
        if ($autoAssign && Auth::user()->hasRole('Customer')) {
            $csRep = User::role('Customer Service')
                ->inRandomOrder()
                ->first();
            
            if ($csRep) {
                $ticket->assigned_to = $csRep->id;
                $ticket->save();
            }
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket created successfully');
    }

    public function show(Ticket $ticket)
    {
        // Authorization check
        if (Auth::user()->hasRole('Customer') && $ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $ticket->load('user', 'order', 'assignedTo', 'responses.user');
        $customerServiceReps = User::role('Customer Service')->get();

        return view('tickets.show', compact('ticket', 'customerServiceReps'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        // Only staff can update ticket details
        if (Auth::user()->hasRole('Customer')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'sometimes|required|in:open,pending,resolved,closed',
            'priority' => 'sometimes|required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket->update($validated);

        // Add internal note about the change if provided
        if ($request->filled('note')) {
            TicketResponse::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'message' => $request->note,
                'is_internal' => true
            ]);
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully');
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'is_internal' => 'boolean',
        ]);
        
        // Customers cannot create internal notes
        if (Auth::user()->hasRole('Customer')) {
            $validated['is_internal'] = false;
        }
        
        $validated['user_id'] = Auth::id();
        $validated['ticket_id'] = $ticket->id;
        
        TicketResponse::create($validated);
        
        // Update ticket status when customer or staff responds
        if (Auth::user()->hasRole('Customer')) {
            // When customer responds to a resolved ticket, reopen it
            if ($ticket->status === Ticket::STATUS_RESOLVED) {
                $ticket->status = Ticket::STATUS_OPEN;
                $ticket->save();
            }
        } else {
            // Staff response marks ticket as pending customer response
            if ($ticket->status === Ticket::STATUS_OPEN) {
                $ticket->status = Ticket::STATUS_PENDING;
                $ticket->save();
            }
        }
        
        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Response added successfully');
    }

    public function close(Request $request, Ticket $ticket)
    {
        // Customers can only close their own tickets
        if (Auth::user()->hasRole('Customer') && $ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $ticket->status = Ticket::STATUS_CLOSED;
        $ticket->save();
        
        // Add note about who closed the ticket
        TicketResponse::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => 'Ticket closed by ' . Auth::user()->name,
            'is_internal' => !Auth::user()->hasRole('Customer'),
        ]);
        
        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket closed successfully');
    }

    public function reopen(Request $request, Ticket $ticket)
    {
        // Customers can only reopen their own tickets
        if (Auth::user()->hasRole('Customer') && $ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $ticket->status = Ticket::STATUS_OPEN;
        $ticket->save();
        
        // Add note about who reopened the ticket
        TicketResponse::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => 'Ticket reopened by ' . Auth::user()->name,
            'is_internal' => !Auth::user()->hasRole('Customer'),
        ]);
        
        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket reopened successfully');
    }
}