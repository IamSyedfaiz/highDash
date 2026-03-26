<x-mail::message>
    # New Leave Request Submitted

    **{{ $leaveRequest->user->name }}** has submitted a new leave request.

    **Type:** {{ ucfirst($leaveRequest->type) }}
    **From:** {{ \Carbon\Carbon::parse($leaveRequest->from_date)->format('M d, Y') }}
    **To:** {{ \Carbon\Carbon::parse($leaveRequest->to_date)->format('M d, Y') }}

    **Reason:**
    <div style="background: #f8fafc; padding: 10px; border-left: 4px solid #4f46e5; border-radius: 4px;">
        {{ $leaveRequest->reason ?? 'Not provided' }}
    </div>

    <x-mail::button :url="route('admin.leaves.index')">
        Review Leave Request
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>