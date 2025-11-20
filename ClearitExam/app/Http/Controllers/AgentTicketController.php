<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class AgentTicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('assigned_to', Auth::id())->get();
        return view('agent.tickets.index', compact('tickets'));
    }
    public function take($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->assigned_to = Auth::id();
        $ticket->status = 'in_progress';
        $ticket->save();

        return redirect()->route('agent.tickets.index')->with('success', 'Ticket taken successfully.');
    }
    public function complete($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 'resolved';
        $ticket->save();

        return redirect()->route('agent.tickets.index')->with('success', 'Ticket completed successfully.');
    }

    public function downloadDocument($ticketId, $documentId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $document = $ticket->documents()->where('id', $documentId)->firstOrFail();
        return response()->download(storage_path('app/public/' . $document->path), $document->original_name);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,in_progress,resolved,closed',
            'comments' => 'nullable|string',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->status = $request->status;
        if ($request->comments) {
            $ticket->comments = $request->comments;
        }
        $ticket->assigned_to = Auth::id();
        $ticket->save();

        return redirect()->route('agent.tickets.show', $id)->with('success', 'Ticket updated successfully.');
    }
    
}
