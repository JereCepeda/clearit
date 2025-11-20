<!-- Document Request Modals for Agent -->
@if(auth()->user()->hasRole('agent') || auth()->user()->hasRole('admin'))
    @php
        $agentTickets = collect();
        if(isset($myTickets)) {
            $agentTickets = $agentTickets->merge($myTickets);
        }
        if(isset($newTickets)) {
            $agentTickets = $agentTickets->merge($newTickets);
        }
    @endphp
    
    @foreach($agentTickets->unique('id') as $agentTicket)
        @if($agentTicket->assigned_agent_id === auth()->id() && $agentTicket->status === 'in_progress')
        <div class="modal fade" id="requestDocsModal{{ $agentTicket->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">Solicitar Documentos - Ticket #{{ $agentTicket->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('agent.tickets.request-documents', $agentTicket->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="requested_documents{{ $agentTicket->id }}" class="form-label">Documentos Requeridos:</label>
                                <textarea class="form-control" id="requested_documents{{ $agentTicket->id }}" name="requested_documents" rows="3" required 
                                          placeholder="Especifica qué documentos necesitas que suba el cliente..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-warning"><i class="bi bi-send me-1"></i>Solicitar Documentos</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @endforeach
@endif

<!-- Ticket Detail Modal -->
<div class="modal fade" id="ticketModal{{ $ticket->id }}" tabindex="-1" role="dialog" aria-labelledby="ticketModalLabel{{ $ticket->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="ticketModalLabel{{ $ticket->id }}">
                    <i class="bi bi-file-text me-2"></i>Ticket #{{ $ticket->id }} - {{ $ticket->name }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <!-- Vista normal -->
            <div id="viewMode{{ $ticket->id }}" class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong><i class="bi bi-info-circle me-1"></i>Status:</strong>
                        @if($ticket->status === 'new')
                            <span class="badge bg-primary ms-2"><i class="bi bi-clock me-1"></i>New</span>
                        @elseif($ticket->status === 'in_progress')
                            <span class="badge bg-warning text-dark ms-2"><i class="bi bi-hourglass-split me-1"></i>In Progress</span>
                        @else
                            <span class="badge bg-success ms-2"><i class="bi bi-check-circle me-1"></i>Completed</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <strong><i class="bi bi-tag me-1"></i>Type:</strong> 
                        @if($ticket->type == 1) Import
                        @else Export  
                        @endif
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong><i class="bi bi-truck me-1"></i>Transport Mode:</strong> {{ ucfirst($ticket->transport_mode) }}
                    </div>
                    <div class="col-md-6">
                        <strong><i class="bi bi-geo-alt me-1"></i>Country:</strong> {{ $ticket->country }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-12">
                        <strong><i class="bi bi-box me-1"></i>Product:</strong> {{ $ticket->transported_product }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong><i class="bi bi-calendar me-1"></i>Created:</strong> {{ $ticket->created_at->format('F j, Y g:i A') }}
                    </div>
                    <div class="col-md-6">
                        <strong><i class="bi bi-person me-1"></i>Created by:</strong> {{ $ticket->creator->name }}
                    </div>
                </div>

                @if($ticket->assigned_agent_id)
                    <div class="row mb-3">
                        <div class="col-12">
                            <strong><i class="bi bi-person-badge me-1"></i>Assigned Agent:</strong> {{ $ticket->assignedAgent->name }}
                        </div>
                    </div>
                @endif

                @if($ticket->comments)
                    <div class="row mb-3">
                        <div class="col-12">
                            <strong><i class="bi bi-chat-dots me-1"></i>Comments:</strong>
                            <div class="border p-3 bg-light rounded mt-2">{{ $ticket->comments }}</div>
                        </div>
                    </div>
                @endif

                @if($ticket->agent_comments)
                    <div class="row mb-3">
                        <div class="col-12">
                            <strong><i class="bi bi-chat-dots text-warning me-1"></i>Agent Comments:</strong>
                            <div class="border p-3 bg-warning bg-opacity-10 rounded mt-2">{{ $ticket->agent_comments }}</div>
                        </div>
                    </div>
                @endif

                @if($ticket->requested_documents)
                    <div class="row mb-3">
                        <div class="col-12">
                            <strong><i class="bi bi-file-earmark-text text-info me-1"></i>Requested Documents:</strong>
                            <div class="border p-3 bg-info bg-opacity-10 rounded mt-2">{{ $ticket->requested_documents }}</div>
                        </div>
                    </div>
                @endif

                @if($ticket->pending_documents && is_array($ticket->pending_documents) && count($ticket->pending_documents) > 0)
                    <div class="row mb-3">
                        <div class="col-12">
                            <strong><i class="bi bi-file-earmark me-1"></i>Documentos Adjuntos:</strong>
                            <div class="mt-2">
                                @foreach($ticket->pending_documents as $index => $doc)
                                    @if(is_array($doc) && isset($doc['original_name']))
                                        <div class="d-flex justify-content-between align-items-center border p-2 mb-2 rounded">
                                            <span><i class="bi bi-file-earmark me-1"></i>{{ $doc['original_name'] }}</span>
                                            <div>
                                                @hasrole('agent|admin')
                                                    <a href="{{ route('agent.tickets.download', ['ticket' => $ticket->id, 'document' => $index]) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-download me-1"></i>Descargar
                                                    </a>
                                                @endhasrole
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex justify-content-between align-items-center border p-2 mb-2 rounded bg-light">
                                            <span><i class="bi bi-exclamation-triangle text-warning me-1"></i>Document format error: {{ is_string($doc) ? $doc : 'Unknown format' }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Formulario de edición -->
            <div id="editMode{{ $ticket->id }}" class="modal-body" style="display: none;">
                <form id="editForm{{ $ticket->id }}" action="{{ route('customer.tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="edit_name{{ $ticket->id }}" class="form-label">Nombre del Ticket</label>
                            <input type="text" class="form-control" id="edit_name{{ $ticket->id }}" name="name" value="{{ $ticket->name }}" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_type{{ $ticket->id }}" class="form-label">Tipo</label>
                            <select class="form-control" id="edit_type{{ $ticket->id }}" name="type" required>
                                <option value="1" {{ $ticket->type == 1 ? 'selected' : '' }}>Import</option>
                                <option value="2" {{ $ticket->type == 2 ? 'selected' : '' }}>Export</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_transport_mode{{ $ticket->id }}" class="form-label">Modo de Transporte</label>
                            <select class="form-control" id="edit_transport_mode{{ $ticket->id }}" name="transport_mode" required>
                                <option value="air" {{ $ticket->transport_mode == 'air' ? 'selected' : '' }}>Aéreo</option>
                                <option value="sea" {{ $ticket->transport_mode == 'sea' ? 'selected' : '' }}>Marítimo</option>
                                <option value="land" {{ $ticket->transport_mode == 'land' ? 'selected' : '' }}>Terrestre</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_country{{ $ticket->id }}" class="form-label">País</label>
                            <input type="text" class="form-control" id="edit_country{{ $ticket->id }}" name="country" value="{{ $ticket->country }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_transported_product{{ $ticket->id }}" class="form-label">Producto a Transportar</label>
                            <input type="text" class="form-control" id="edit_transported_product{{ $ticket->id }}" name="transported_product" value="{{ $ticket->transported_product }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_comments{{ $ticket->id }}" class="form-label">Comentarios</label>
                        <textarea class="form-control" id="edit_comments{{ $ticket->id }}" name="comments" rows="3">{{ $ticket->comments }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_documents{{ $ticket->id }}" class="form-label">Agregar Documentos</label>
                        <input type="file" class="form-control" id="edit_documents{{ $ticket->id }}" name="documents[]" multiple accept=".pdf,.jpg,.png,.docx">
                        <div class="form-text">Selecciona archivos para agregar a los documentos existentes.</div>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer">
                <!-- Botones modo vista -->
                <div id="viewButtons{{ $ticket->id }}">
                    @hasrole('user')
                        @if($ticket->status !== 'completed' && $ticket->created_by === auth()->id())
                            <button type="button" class="btn btn-primary" onclick="toggleEditMode({{ $ticket->id }})">
                                <i class="bi bi-pencil me-1"></i>Edit Ticket
                            </button>
                        @endif
                    @endhasrole
                    
                    @hasrole('agent')
                        @if($ticket->status === 'new')
                            <form action="{{ route('agent.tickets.take', $ticket->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-hand-thumbs-up me-1"></i>Take Ticket
                                </button>
                            </form>
                        @elseif($ticket->status === 'in_progress' && $ticket->assigned_agent_id === auth()->id())
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#requestDocsModal{{ $ticket->id }}">
                                <i class="bi bi-file-earmark-plus me-1"></i>Request Documents
                            </button>
                            <form action="{{ route('agent.tickets.complete', $ticket->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>Complete Ticket
                                </button>
                            </form>
                        @endif
                    @endhasrole
                    
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Close
                    </button>
                </div>

                <!-- Botones modo edición -->
                <div id="editButtons{{ $ticket->id }}" style="display: none;">
                    <button type="button" class="btn btn-success" onclick="submitEditForm({{ $ticket->id }})">
                        <i class="bi bi-check-lg me-1"></i>Save Changes
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="toggleEditMode({{ $ticket->id }})">
                        <i class="bi bi-x-lg me-1"></i>Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if modal exists
    const modal = document.getElementById('ticketModal{{ $ticket->id }}');
    if (modal) {
        // Add event listeners to debug modal events if needed
        modal.addEventListener('show.bs.modal', function (event) {
            console.log('Modal {{ $ticket->id }} opening');
        });
    }
});

if (typeof window.toggleEditMode === 'undefined') {
    window.toggleEditMode = function(ticketId) {
        const viewMode = document.getElementById('viewMode' + ticketId);
        const editMode = document.getElementById('editMode' + ticketId);
        const viewButtons = document.getElementById('viewButtons' + ticketId);
        const editButtons = document.getElementById('editButtons' + ticketId);
        
        if (viewMode && editMode && viewButtons && editButtons) {
            if (viewMode.style.display === 'none') {
                viewMode.style.display = 'block';
                editMode.style.display = 'none';
                viewButtons.style.display = 'block';
                editButtons.style.display = 'none';
            } else {
                viewMode.style.display = 'none';
                editMode.style.display = 'block';
                viewButtons.style.display = 'none';
                editButtons.style.display = 'block';
            }
        }
    };
}

if (typeof window.submitEditForm === 'undefined') {
    window.submitEditForm = function(ticketId) {
        const form = document.getElementById('editForm' + ticketId);
        if (form) {
            form.submit();
        }
    };
}
</script>
@endpush