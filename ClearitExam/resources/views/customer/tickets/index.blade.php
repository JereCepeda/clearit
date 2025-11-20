
@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar izquierdo - Funcionalidades -->
        <div class="col-lg-6">
            <div class="card h-100 shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-gear-fill me-2"></i>Available Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3 mb-4">
                        @if(auth()->user()->hasRole(['user', 'admin']))
                        <a href="{{ route('customer.tickets.create') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-circle me-2"></i> Create New Ticket
                        </a>
                        @endif
                        
                        @if(auth()->user()->hasRole(['user', 'agent', 'admin']))
                        <button class="btn btn-outline-secondary" disabled>
                            <i class="bi bi-list-ul me-2"></i> View All Tickets (Current Page)
                        </button>
                        @endif
                        
                        @if(auth()->user()->hasRole(['agent', 'admin']))
                        <button class="btn btn-outline-info">
                            <i class="bi bi-bar-chart me-2"></i> Ticket Statistics
                        </button>
                        @endif
                        
                        @if(auth()->user()->hasRole(['user', 'admin']))
                        <button class="btn btn-outline-warning">
                            <i class="bi bi-upload me-2"></i> Upload Documents
                        </button>
                        @endif
                    </div>
                    
                    <hr>
                    
                    <h6 class="text-dark mb-3"><i class="bi bi-info-circle me-2"></i>System Features</h6>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-ticket-fill text-primary me-3 fs-4"></i>
                                <div>
                                    <strong>Ticket Management</strong>
                                    <p class="mb-0 text-muted small">Create and track your import/export tickets</p>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-paperclip text-success me-3 fs-4"></i>
                                <div>
                                    <strong>Document Handling</strong>
                                    <p class="mb-0 text-muted small">Upload and manage required documentation</p>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-bell-fill text-warning me-3 fs-4"></i>
                                <div>
                                    <strong>Real-time Notifications</strong>
                                    <p class="mb-0 text-muted small">Get updates on ticket status changes</p>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-people-fill text-info me-3 fs-4"></i>
                                <div>
                                    <strong>Agent Collaboration</strong>
                                    <p class="mb-0 text-muted small">Work directly with clearance agents</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Panel derecho - Lista de Tickets -->
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>My Tickets - {{ $customer->name }}</h5>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    @forelse($tickets as $ticket)
                        <div class="card mb-3 border-light shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-dark">
                                    <i class="bi bi-file-text me-2"></i>{{ $ticket->name }}
                                </h6>
                                <div>
                                    @if($ticket->status === 'new')
                                        <span class="badge bg-primary"><i class="bi bi-clock me-1"></i>New</span>
                                    @elseif($ticket->status === 'in_progress')
                                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>In Progress</span>
                                    @else
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Completed</span>
                                    @endif
                                    
                                    <button class="btn btn-sm btn-dark ms-2" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#ticketModal{{ $ticket->id }}">
                                        <i class="bi bi-eye me-1"></i>View Details
                                    </button>
                                </div>
                            </div>
                            <div class="card-body bg-white">
                                <div class="row text-dark">
                                    <div class="col-6 mb-2">
                                        <strong><i class="bi bi-tag me-1"></i>Type:</strong> {{ $ticket->type }}
                                    </div>
                                    <div class="col-6 mb-2">
                                        <strong><i class="bi bi-truck me-1"></i>Transport:</strong> {{ $ticket->transport_mode }}
                                    </div>
                                    <div class="col-6 mb-2">
                                        <strong><i class="bi bi-geo-alt me-1"></i>Country:</strong> {{ $ticket->country }}
                                    </div>
                                    <div class="col-6 mb-2">
                                        <strong><i class="bi bi-calendar me-1"></i>Created:</strong> {{ $ticket->created_at->format('M j, Y') }}
                                    </div>
                                </div>
                                
                                @if($ticket->pending_documents && count($ticket->pending_documents) > 0)
                                    @foreach($ticket->pending_documents as $doc)
                                        <div class="alert alert-warning mt-2">
                                            <i class="bi bi-exclamation-triangle me-2"></i>{{ $doc['original_name']  }}
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        @include('components.ticket.modal', ['ticket' => $ticket])
                        
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <h4 class="text-muted mt-3">No tickets found</h4>
                            <p class="text-muted">You haven't created any tickets yet.</p>
                            @if(auth()->user()->hasRole(['user', 'admin']))
                            <a href="{{ route('customer.tickets.create') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle me-2"></i>Create Your First Ticket
                            </a>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection