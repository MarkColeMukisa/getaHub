<x-mail::message>
    # Introduction

    Tenant has been created!
    {{ $tenant->name }}
    {{ $tenant->contact }}
    {{ $tenant->room_number }}

    <x-mail::button :url="''">
        Button Text
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>