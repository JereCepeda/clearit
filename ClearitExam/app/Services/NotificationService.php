<?php

namespace App\Services;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function notifyTicketCommentUpdate(Ticket $ticket, $oldComments, $newComments)
    {
        if ($oldComments != $newComments && $ticket->created_by != Auth::id()) {
            $customer = $ticket->creator;
            $updater = Auth::user();
            
            if (!$customer || !$updater) {
                return; // Safety check
            }
            
            try {
                // Por ahora solo log - puedes cambiar por email real después
                Log::info("Notification: Comment updated on ticket #{$ticket->id}", [
                    'ticket_id' => $ticket->id,
                    'customer_email' => $customer->email,
                    'old_comments' => $oldComments,
                    'new_comments' => $newComments,
                    'updated_by' => Auth::user()->name
                ]);
                
                // TODO: Implementar envío de email real
                // Mail::to($customer->email)->send(new TicketCommentUpdated($ticket, $oldComments, $newComments));
                
            } catch (\Exception $e) {
                Log::error("Failed to send comment update notification", [
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
    
    public function notifyNewTicket(Ticket $ticket)
    {
        if ($ticket->status === 'new') {
            $agents = User::whereHas('roles', function ($query) {
                $query->where('name', 'agent');
            })->get();
            
            foreach ($agents as $agent) {
                try {
                    Log::info("Notification: New ticket available", [
                        'ticket_id' => $ticket->id,
                        'agent_email' => $agent->email,
                        'ticket_name' => $ticket->name,
                        'customer' => $ticket->creator->name
                    ]);
                    
                    // TODO: Implementar envío de email real
                    // Mail::to($agent->email)->send(new NewTicketAvailable($ticket));
                    
                } catch (\Exception $e) {
                    Log::error("Failed to send new ticket notification", [
                        'ticket_id' => $ticket->id,
                        'agent_id' => $agent->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
    }
    
    public function notifyTicketCompleted(Ticket $ticket)
    {
        if ($ticket->status === 'completed') {
            $customer = $ticket->creator;
            
            try {
                Log::info("Notification: Ticket completed", [
                    'ticket_id' => $ticket->id,
                    'customer_email' => $customer->email,
                    'ticket_name' => $ticket->name,
                    'completed_by' => $ticket->assignedAgent ? $ticket->assignedAgent->name : 'Unknown'
                ]);
                
                // TODO: Implementar envío de email real
                // Mail::to($customer->email)->send(new TicketCompleted($ticket));
                
            } catch (\Exception $e) {
                Log::error("Failed to send completion notification", [
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
    
    public function notifyTicketAssigned(Ticket $ticket)
    {
        if ($ticket->assigned_agent_id && $ticket->status === 'in_progress') {
            $customer = $ticket->creator;
            $agent = $ticket->assignedAgent;
            
            if (!$customer || !$agent) {
                return; // Safety check
            }
            
            try {
                Log::info("Notification: Ticket assigned to agent", [
                    'ticket_id' => $ticket->id,
                    'customer_email' => $customer->email,
                    'agent_name' => $agent->name,
                    'ticket_name' => $ticket->name
                ]);
                
                // TODO: Implementar envío de email real
                // Mail::to($customer->email)->send(new TicketAssigned($ticket));
                
            } catch (\Exception $e) {
                Log::error("Failed to send ticket assignment notification", [
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
    
    public function notifyDocumentRequested(Ticket $ticket, $requestedDocuments)
    {
        $customer = $ticket->creator;
        
        try {
            Log::info("Notification: Documents requested", [
                'ticket_id' => $ticket->id,
                'customer_email' => $customer->email,
                'requested_by' => Auth::user()->name,
                'requested_documents' => $requestedDocuments
            ]);
            
            // TODO: Implementar envío de email real
            // Mail::to($customer->email)->send(new DocumentsRequested($ticket, $requestedDocuments));
            
        } catch (\Exception $e) {
            Log::error("Failed to send document request notification", [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}