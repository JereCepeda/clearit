<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CustomertTicketController extends Controller
{
    public function index(): View
    {
        /** @var User $customer */
        $customer = Auth::user();
        $tickets = $customer->createdTickets()->get();

        return view('customer.tickets.index', compact('tickets', 'customer'));
    }
    
    public function show($id)
    {
        return view('customer.tickets.show', ['ticketId' => $id]);
    }

    public function create()
    {
        return view('customer.tickets.create');
    }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:1,2,3',
            'transport_mode' => 'required|in:air,sea,land',
            'country' => 'required|string|max:255',
            'transported_product' => 'required|string|max:255',
            'comments' => 'nullable|string',
            'documents.*' => 'file|mimes:pdf,jpg,png,docx|max:2048'
        ]);

        $ticket = Ticket::create([
            'name' => $request->name,
            'type' => $request->type,
            'transport_mode' => $request->transport_mode,
            'country' => $request->country,
            'transported_product' => $request->transported_product,
            'comments' => $request->comments,
            'created_by' => Auth::id(),
            'status' => 'new'
        ]);

        if ($request->hasFile('documents')) {
            $documents = [];
            foreach ($request->file('documents') as $file) {
                $path = $file->store('tickets/' . $ticket->id, 'public');
                $documents[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'uploaded_at' => now()
                ];
            }
            $ticket->update(['pending_documents' => $documents]);
        }

        // TODO: Send notification to agents

        return redirect()->route('customer.tickets.index')
                        ->with('success', 'Ticket created successfully.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:1,2,3',
            'transport_mode' => 'required|in:air,sea,land',
            'country' => 'required|string|max:255',
            'transported_product' => 'required|string|max:255',
            'comments' => 'nullable|string',
            'documents.*' => 'file|mimes:pdf,jpg,png,docx|max:2048'
        ]);

        $ticket = Ticket::findOrFail($id);
        
        if ($ticket->created_by !== Auth::id() && !Auth::user()->hasRole('admin')) {
            return redirect()->route('customer.tickets.index')
                        ->with('error', 'Permission denied, you do not have access to edit this ticket.');
        }

        $ticket->update([
            'name' => $request->name,
            'type' => $request->type,
            'transport_mode' => $request->transport_mode,
            'country' => $request->country,
            'transported_product' => $request->transported_product,
            'comments' => $request->comments,
        ]);

        if ($request->hasFile('documents')) {
            $existingDocs = $ticket->pending_documents ?: [];
            foreach ($request->file('documents') as $file) {
                $path = $file->store('tickets/' . $ticket->id, 'public');
                $existingDocs[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'uploaded_at' => now()
                ];
            }
            $ticket->update(['pending_documents' => $existingDocs]);
        }

        return redirect()->route('customer.tickets.index')
                        ->with('success', 'Ticket updated successfully.');
    }
}
