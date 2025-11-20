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

    // Accessor to ensure pending_documents is always an array
    public function getPendingDocumentsAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        
        if (is_string($value)) {
            // Try to decode JSON string
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return is_array($decoded) ? $decoded : [];
            }
            // If not valid JSON, return empty array
            return [];
        }
        
        if (is_array($value)) {
            return $value;
        }
        
        return [];
    }

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
