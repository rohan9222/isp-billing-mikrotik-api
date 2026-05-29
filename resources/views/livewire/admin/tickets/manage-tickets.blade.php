<div>
    <x-slot name="header">
        {{ __('Support Tickets') }}
    </x-slot>

    <div class="card">
        <div class="card-body row">
            
            <div class="col-12">
                <div class="d-flex justify-content-between p-2 flex-wrap gap-2">
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" wire:model.live="perPage" style="width: 80px;">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>

                        <select class="form-select form-select-sm" wire:model.live="statusFilter" style="width: 150px;">
                            <option value="">All Statuses</option>
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>

                        <select class="form-select form-select-sm" wire:model.live="priorityFilter" style="width: 150px;">
                            <option value="">All Priorities</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                    <input type="text" class="form-control form-control-sm w-250" wire:model.live="search" placeholder="Search tickets..." aria-label="Search">
                </div>
            </div>

            <div class="col-12 table-responsive mt-3">
                <table class="table table-striped table-hover table-bordered border-success">
                    <thead>
                        <tr>
                            <th class="table-success border border-success" scope="col">Ticket No</th>
                            <th class="table-success border border-success" scope="col">Customer</th>
                            <th class="table-success border border-success" scope="col">Subject</th>
                            <th class="table-success border border-success" scope="col">Category</th>
                            <th class="table-success border border-success" scope="col">Priority</th>
                            <th class="table-success border border-success" scope="col">Status</th>
                            <th class="table-success border border-success" scope="col">Submitted</th>
                            <th class="table-success border border-success" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tickets as $ticket)
                        <tr>
                            <th scope="row" class="text-primary font-monospace">{{ $ticket->ticket_no }}</th>
                            <td>
                                <strong>{{ $ticket->customer->customer_name ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $ticket->customer_unique_id }} ({{ $ticket->ppp_username }})</small>
                            </td>
                            <td>
                                <strong>{{ $ticket->subject }}</strong><br>
                                <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">{{ $ticket->description }}</small>
                            </td>
                            <td class="text-capitalize">{{ $ticket->category }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    match($ticket->priority) {
                                        'high' => 'danger',
                                        'medium' => 'warning text-dark',
                                        'low' => 'success',
                                        default => 'secondary'
                                    }
                                }}">{{ ucfirst($ticket->priority) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ 
                                    match($ticket->status) {
                                        'open' => 'warning text-dark',
                                        'in_progress' => 'primary',
                                        'resolved' => 'success',
                                        'closed' => 'secondary',
                                        default => 'secondary'
                                    }
                                }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                            </td>
                            <td>
                                <span title="{{ $ticket->created_at }}">{{ $ticket->created_at->diffForHumans() }}</span>
                            </td>
                            <td>
                                <button wire:click="showReplyModal({{ $ticket->id }})" class="btn btn-primary btn-sm">
                                    <i class="bi bi-chat-left-text"></i> Reply/Manage
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-danger">
                                <strong>No Tickets Found!</strong>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="col-12 mt-2">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>

    {{-- Reply Modal --}}
    @if($confirmingReply && $selectedTicket)
    <x-dialog-modal wire:model.live="confirmingReply" maxWidth="2xl">
        <x-slot name="title">
            Manage Support Ticket - {{ $selectedTicket->ticket_no }}
        </x-slot>

        <x-slot name="content">
            <div class="mb-3 text-dark">
                <strong>Customer:</strong> {{ $selectedTicket->customer->customer_name ?? 'N/A' }} ({{ $selectedTicket->customer_unique_id }}) {{ $selectedTicket->customer->mobile ? ' || Mobile: ' . $selectedTicket->customer->mobile : ''}} ({{ $selectedTicket->ppp_username}})
            </div>
            <div class="mb-3 text-dark">
                <strong>Subject:</strong> {{ $selectedTicket->subject }}
            </div>
            <div class="mb-3 text-dark">
                <strong>Category:</strong> <span class="text-capitalize">{{ $selectedTicket->category }}</span> | 
                <strong>Priority:</strong> <span class="badge bg-{{ 
                    match($selectedTicket->priority) {
                        'high' => 'danger',
                        'medium' => 'warning text-dark',
                        'low' => 'success',
                        default => 'secondary'
                    }
                }}">{{ ucfirst($selectedTicket->priority) }}</span>
            </div>
            <div class="mb-3 card bg-light p-3">
                <strong>Description:</strong>
                <p class="mb-0 text-dark whitespace-pre-line mt-1">{{ $selectedTicket->description }}</p>
            </div>

            <form wire:submit.prevent="submitReply">
                <div class="mb-3 text-dark">
                    <label for="adminReply" class="form-label fw-bold">Admin Reply / Response</label>
                    <textarea id="adminReply" class="form-control @error('adminReply') is-invalid @enderror" 
                        wire:model="adminReply" rows="5" placeholder="Enter support team response..."></textarea>
                    <x-error name="adminReply" />
                </div>

                <div class="mb-3 row text-dark">
                    <label for="status" class="col-md-3 col-form-label fw-bold">Update Status</label>
                    <div class="col-md-6">
                        <select id="status" class="form-select @error('status') is-invalid @enderror" wire:model="status">
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                        <x-error name="status" />
                    </div>
                </div>

                <div class="mb-3 text-end">
                    <button type="button" class="btn btn-secondary me-2" wire:click="$set('confirmingReply', false)">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Response</button>
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            {{-- blank --}}
        </x-slot>
    </x-dialog-modal>
    @endif
</div>
