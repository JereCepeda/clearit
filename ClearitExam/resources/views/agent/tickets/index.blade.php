
@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-clock me-2"></i>Tickets Nuevos</h5>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    @forelse($newTickets as $ticket)
                        <div class="card mb-3 border-light shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-dark">
                                    <i class="bi bi-file-text me-2"></i>{{ $ticket->name }}
                                </h6>
                                <div>
                                    <span class="badge bg-primary"><i class="bi bi-clock me-1"></i>New</span>
                                    <button class="btn btn-sm btn-dark ms-2" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#ticketModal{{ $ticket->id }}">
                                        <i class="bi bi-eye me-1"></i>Ver Detalles
                                    </button>
                                </div>
                            </div>
                            <div class="card-body bg-white">
                                <div class="row text-dark">
                                    <div class="col-6 mb-2">
                                        <strong><i class="bi bi-tag me-1"></i>Tipo:</strong> {{ $ticket->type }}
                                    </div>
                                    <div class="col-6 mb-2">
                                        <strong><i class="bi bi-truck me-1"></i>Transporte:</strong> {{ $ticket->transport_mode }}
                                    </div>
                                    <div class="col-6 mb-2">
                                        <strong><i class="bi bi-geo-alt me-1"></i>País:</strong> {{ $ticket->country }}
                                    </div>
                                    <div class="col-6 mb-2">
                                        <strong><i class="bi bi-person me-1"></i>Cliente:</strong> {{ $ticket->creator->name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('components.ticket.modal', ['ticket' => $ticket])
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <h4 class="text-muted mt-3">No hay tickets nuevos</h4>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-person-check me-2"></i>Mis Tickets Asignados</h5>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    @forelse($myTickets as $ticket)
                        <div class="card mb-3 border-light shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-dark">
                                    <i class="bi bi-file-text me-2"></i>{{ $ticket->name }}
                                </h6>
                                <div>
                                    @if($ticket->status === 'in_progress')
                                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>En Progreso</span>
                                    @elseif($ticket->status === 'pending_documents')
                                        <span class="badge bg-info"><i class="bi bi-file-earmark me-1"></i>Pendiente Docs</span>
                                    @else
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Completado</span>
                                    @endif
                                    <button class="btn btn-sm btn-dark ms-2" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#ticketModal{{ $ticket->id }}">
                                        <i class="bi bi-eye me-1"></i>Ver Detalles
                                    </button>
                                </div>
                            </div>
                            <div class="card-body bg-white">
                                <div class="row text-dark">
                                    <div class="col-6 mb-2">
                                        <strong><i class="bi bi-tag me-1"></i>Tipo:</strong> {{ $ticket->type }}
                                    </div>
                                    <div class="col-6 mb-2">
                                        <strong><i class="bi bi-truck me-1"></i>Transporte:</strong> {{ $ticket->transport_mode }}
                                    </div>
                                    <div class="col-6 mb-2">
                                        <strong><i class="bi bi-person me-1"></i>Cliente:</strong> {{ $ticket->creator->name }}
                                    </div>
                                    <div class="col-6 mb-2">
                                        <strong><i class="bi bi-calendar me-1"></i>Asignado:</strong> {{ $ticket->updated_at->format('M j, Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('components.ticket.modal', ['ticket' => $ticket])
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-clipboard-x display-1 text-muted"></i>
                            <h4 class="text-muted mt-3">No tienes tickets asignados</h4>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection