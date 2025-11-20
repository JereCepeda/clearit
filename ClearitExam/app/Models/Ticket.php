<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'transport_mode',
        'country',
        'status',
        'transported_product',
        'comments',
        'created_by',
        'assigned_agent_id',
        'pending_documents',
        'last_updated_by',
        'documents_requested_at',
    ];

    protected $casts = [
        'type' => 'integer',
        'pending_documents' => 'array',
        'documents_requested_at' => 'datetime',
    ];

    // Relaciones
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    public function lastUpdatedBy()
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }
}
