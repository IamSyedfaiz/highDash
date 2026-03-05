<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_name',
        'email',
        'phone',
        'phone_1',
        'phone_2',
        'city',
        'state',
        'address',
        'lead_source',
        'business_type',
        'status',
        'calling_status',
        'feedback',
        'assigned_to'
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function followUps()
    {
        return $this->hasMany(LeadFollowUp::class);
    }
}
