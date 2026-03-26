<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    public const STATUSES = [
        'NOT OPEN',
        'WRNG NO',
        'CALLBACK',
        'CALLBACK_UNI',
        'CALLBACK PC',
        'CO_CLOSED',
        'NON TARGET',
        'TELEDUM',
        'TELEDMUFLWP',
        'CRM APPMT',
        'E_APPMT',
        'GREAT PGIFC',
        'A LOT OF PGIFU',
        'VIDEO OMU',
        'VIDEO PG',
        'CB_POST_DMU',
        'CB_POST PG'
    ];

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
        'prospect_status',
        'calling_status',
        'feedback',
        'assigned_to',
        'name',
        'designation',
        'add_distribution',
        'keywords'
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
