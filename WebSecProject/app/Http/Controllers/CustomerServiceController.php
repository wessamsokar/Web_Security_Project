<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CustomerServiceController extends Controller
{
    public function dashboard()
    {
        // Get stats for the dashboard
        $openTickets = Ticket::where('status', Ticket::STATUS_OPEN)->count();
        $pendingTickets = Ticket::where('status', Ticket::STATUS_PENDING)->count();
        $resolvedTickets = Ticket::where('status', Ticket::STATUS_RESOLVED)->count();
        $closedTickets = Ticket::where('status', Ticket::STATUS_CLOSED)->count();
        
        $urgentTickets = Ticket::where('priority', Ticket::PRIORITY_URGENT)
            ->whereIn('status', [Ticket::STATUS_OPEN, Ticket::STATUS_PENDING])
            ->count();
            
        $myTickets = Ticket::where('assigned_to', Auth::id())
            ->whereIn('status', [Ticket::STATUS_OPEN, Ticket::STATUS_PENDING])
            ->count();
            
        $recentTickets = Ticket::with('user')
            ->whereIn('status', [Ticket::STATUS_OPEN, Ticket::STATUS_PENDING])
            ->latest()
            ->limit(5)
            ->get();
            
        $customerCount = User::role('Customer')->count();
        
        return view('customer_service.dashboard', compact(
            'openTickets', 
            'pendingTickets', 
            'resolvedTickets', 
            'closedTickets',
            'urgentTickets',
            'myTickets',
            'recentTickets',
            'customerCount'
        ));
    }
    
    public function userSearch(Request $request)
    {
        $query = User::role('Customer');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        
        $users = $query->paginate(10);
        
        return view('customer_service.users', compact('users'));
    }
    
    public function userDetails(User $user)
    {
        // Check if viewing a customer
        if (!$user->hasRole('Customer')) {
            return redirect()->route('customer-service.user-search')
                ->with('error', 'You can only view details for customers');
        }
        
        $tickets = Ticket::where('user_id', $user->id)->latest()->get();
        $orders = Order::where('user_id', $user->id)->latest()->get();
        
        return view('customer_service.user_details', compact('user', 'tickets', 'orders'));
    }
    
    public function createTicketForUser(User $user)
    {
        // Check if creating for a customer
        if (!$user->hasRole('Customer')) {
            return redirect()->route('customer-service.user-search')
                ->with('error', 'You can only create tickets for customers');
        }
        
        $orders = Order::where('user_id', $user->id)->latest()->get();
        
        return view('customer_service.create_ticket', compact('user', 'orders'));
    }
}
?>