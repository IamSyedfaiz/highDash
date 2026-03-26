<x-mail::message>
    # Leave Request {{ ucfirst($leaveRequest->status) }}

    Hello **{{ $leaveRequest->user->name }}**,

    Your leave request has been **{{ ucfirst($leaveRequest->status) }}** by the administrator.

    **Details of the request:**
    - **Type:** {{ ucfirst($leaveRequest->type) }}
    - **From:** {{ \Carbon\Carbon::parse($leaveRequest->from_date)->format('M d, Y') }}
    - **To:** {{ \Carbon\Carbon::parse($leaveRequest->to_date)->format('M d, Y') }}

    @if($leaveRequest->status === 'approved')
        We hope you have a great time! Please make sure anything urgent is handed over correctly.
    @else
        If you have any questions regarding this decision, please discuss it with your manager.
    @endif

    <x-mail::button :url="url('/')">
        Go to Dashboard
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>