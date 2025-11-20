<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AgentTicketController extends Controller
{
    public function index()
    {
        $newTickets = Ticket::Where('status', 'new')->get();
        $myTickets= Ticket::where('assigned_agent_id', Auth::id())->get();
        return view('agent.tickets.index', compact('newTickets', 'myTickets'));
    }
    public function take($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->assigned_agent_id = Auth::id();
        $ticket->status = 'in_progress';
        $ticket->save();

        return redirect()->route('agent.tickets.index')->with('success', 'Ticket taken successfully.');
    }
    public function complete($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 'completed';
        $ticket->save();

        return redirect()->route('agent.tickets.index')->with('success', 'Ticket completed successfully.');
    }

    public function downloadDocument($ticketId, $documentIndex)
    {
        $ticket = Ticket::findOrFail($ticketId);
        
        if (!$ticket->pending_documents || !isset($ticket->pending_documents[$documentIndex])) {
            return redirect()->back()->with('error', 'Documento no encontrado.');
        }

        $document = $ticket->pending_documents[$documentIndex];
        $path = storage_path('app/public/' . $document['path']);

        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'Archivo no encontrado.');
        }

        return response()->download($path, $document['original_name']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,in_progress,completed',
            'comments' => 'nullable|string',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->status = $request->status;
        if ($request->comments) {
            $ticket->comments = $request->comments;
        }
        $ticket->assigned_agent_id = Auth::id();
        $ticket->save();

        return redirect()->route('agent.tickets.index', $id)->with('success', 'Ticket updated successfully.');
    }
    public function requestDocuments(Request $request, $id)
    {
        $request->validate([
            'requested_documents' => 'required|string',
        ]);

        $ticket = Ticket::findOrFail($id);
        
        // Solo agregar al campo comments que ya existe
        $newComment = "\n\n Agent Document Request \n";
        $newComment .= "Agent: " . Auth::user()->name . "\n";
        $newComment .= "Date: " . now()->format('Y-m-d') . "\n";
        $newComment .= "Requested Documents: " . $request->requested_documents . "\n";

        $ticket->comments = $ticket->comments . $newComment;
        $ticket->save();

        return redirect()->back()->with('success', 'Document request sent to customer.');
    }   
}
