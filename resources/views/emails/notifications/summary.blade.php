<x-mail::message>
    # SMS Notification Verification Report
    **Date:** {{ $report['timestamp'] }}

    This is a structured summary of the tenant bill notifications for Geta.

    <x-mail::panel>
        ### System Overview
        - **Total Registered Tenants:** {{ $report['total_tenants'] }}
        - **Total Bills Recorded:** {{ $report['total_bills'] }}
        - **Successfully Notified Today:** {{ $report['notified_today'] }}
        - **Pending Notifications:** {{ $report['total_pending'] }}
    </x-mail::panel>

    @if(count($report['failed_notifications']) > 0)
    ## ⚠️ Failed Notifications
    The following notifications encountered errors and may require manual intervention.

    <x-mail::table>
        | Tenant | Reason | Attempts |
        | :--- | :--- | :--- |
        @foreach($report['failed_notifications'] as $fail)
        | {{ $fail['tenant'] }} | {{ $fail['reason'] }} | {{ $fail['attempts'] }} |
        @endforeach
    </x-mail::table>
    @else
    ## ✅ All notifications are up-to-date.
    No failure alerts recorded in the current system state.
    @endif

    <x-mail::button :url="config('app.url') . '/dashboard'">
        Go to Dashboard
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }} Automated Reporting
</x-mail::message>